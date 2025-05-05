<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\ApartmentImage;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApartmentController extends Controller
{
    /**
     * Display a listing of apartments owned by the current user.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'owner') {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }
        
        $owner = Owner::where('user_id', $user->id)->first();
        $apartments = Apartment::where('owner_id', $owner->id)
            ->with(['images', 'availableDates', 'bookings'])
            ->get();
        
        return view('apartments.index', compact('apartments'));
    }

    /**
     * Show the form for creating a new apartment.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role !== 'owner') {
            return redirect()->route('home')->with('error', 'Only property owners can add apartments');
        }
        
        return view('apartments.addapartment');
    }

    /**
     * Store a newly created apartment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'owner') {
            return redirect()->route('home')->with('error', 'Only property owners can add apartments');
        }
        
        $owner = Owner::where('user_id', $user->id)->first();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable,maintenance',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create apartment
            $apartment = Apartment::create([
                'owner_id' => $owner->id,
                'title' => $request->title,
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'price_per_night' => $request->price_per_night,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'max_guests' => $request->max_guests,
                'has_wifi' => $request->has_wifi ? true : false,
                'has_parking' => $request->has_parking ? true : false,
                'has_kitchen' => $request->has_kitchen ? true : false,
                'has_air_conditioning' => $request->has_air_conditioning ? true : false,
                'status' => $request->status,
                'rating' => 1, // Default rating for new apartments
               
            ]);
            
            // Handle images
            if ($request->hasFile('images')) {
                $isPrimary = true; // First image is primary
                
                foreach ($request->file('images') as $index => $imageFile) {
                    $imageName = time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $imageFile->extension();
                    $imageFile->storeAs('public/apartments', $imageName);
                    
                    ApartmentImage::create([
                        'apartment_id' => $apartment->id,
                        'image_path' => $imageName,
                        'is_primary' => $isPrimary,
                    ]);
                    
                    // Only first image is primary
                    $isPrimary = false;
                }
            }
            
            DB::commit();
            
            return redirect()->route('profile')
                ->with('success', 'Property added successfully!');
                
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error adding property: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified apartment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $apartment = Apartment::with(['owner.user', 'images', 'availableDates'])
            ->findOrFail($id);
            
        return view('apartments.show', compact('apartment'));
    }

    /**
     * Show the form for editing the specified apartment.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = Auth::user();
        $apartment = Apartment::with(['images'])
            ->findOrFail($id);
            
        // Check if user is the owner
        $owner = Owner::where('user_id', $user->id)->first();
        
        if (!$owner || $apartment->owner_id != $owner->id) {
            return redirect()->route('profile')
                ->with('error', 'You can only edit your own properties');
        }
        
        return view('apartments.edit', compact('apartment'));
    }

    /**
     * Update the specified apartment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $apartment = Apartment::findOrFail($id);
        
        // Check if user is the owner
        $owner = Owner::where('user_id', $user->id)->first();
        
        if (!$owner || $apartment->owner_id != $owner->id) {
            return redirect()->route('profile')
                ->with('error', 'You can only update your own properties');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'status' => 'required|in:available,unavailable,maintenance',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
        ]);
        
        DB::beginTransaction();
        
        try {
            $apartment->update([
                'title' => $request->title,
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'price_per_night' => $request->price_per_night,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'max_guests' => $request->max_guests,
                'has_wifi' => $request->has_wifi ? true : false,
                'has_parking' => $request->has_parking ? true : false,
                'has_kitchen' => $request->has_kitchen ? true : false,
                'has_air_conditioning' => $request->has_air_conditioning ? true : false,
                'status' => $request->status,
            ]);
            
            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $imageFile) {
                    $imageName = time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $imageFile->extension();
                    $imageFile->storeAs('public/apartments', $imageName);
                    
                    ApartmentImage::create([
                        'apartment_id' => $apartment->id,
                        'image_path' => $imageName,
                        'is_primary' => false,
                    ]);
                }
            }
            
            // Set primary image if specified
            if ($request->has('primary_image')) {
                // Reset all images to non-primary
                ApartmentImage::where('apartment_id', $apartment->id)
                    ->update(['is_primary' => false]);
                
                // Set the selected image as primary
                ApartmentImage::where('id', $request->primary_image)
                    ->update(['is_primary' => true]);
            }
            
            DB::commit();
            
            return redirect()->route('profile')
                ->with('success', 'Property updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Error updating property: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified apartment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $apartment = Apartment::findOrFail($id);
        
        // Check if user is the owner
        $owner = Owner::where('user_id', $user->id)->first();
        
        if (!$owner || $apartment->owner_id != $owner->id) {
            return redirect()->route('profile')
                ->with('error', 'You can only delete your own properties');
        }
        
        DB::beginTransaction();
        
        try {
            // Delete all images first
            $images = ApartmentImage::where('apartment_id', $apartment->id)->get();
            foreach ($images as $image) {
                Storage::disk('public')->delete('apartments/' . $image->image_path);
                $image->delete();
            }
            
            $apartment->delete();
            
            DB::commit();
            
            return redirect()->route('profile')
                ->with('success', 'Property deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('profile')
                ->with('error', 'Error deleting property: ' . $e->getMessage());
        }
    }

    /**
     * Remove an apartment image.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyImage($id)
    {
        $user = Auth::user();
        $image = ApartmentImage::findOrFail($id);
        $apartment = Apartment::findOrFail($image->apartment_id);
        
        // Check if user is the owner
        $owner = Owner::where('user_id', $user->id)->first();
        
        if (!$owner || $apartment->owner_id != $owner->id) {
            return redirect()->route('profile')
                ->with('error', 'You can only delete images of your own properties');
        }
        
        // Make sure we're not deleting the only image
        $imageCount = ApartmentImage::where('apartment_id', $apartment->id)->count();
        $isPrimary = $image->is_primary;
        
        try {
            // Delete image file
            Storage::disk('public')->delete('apartments/' . $image->image_path);
            
            // Delete record
            $image->delete();
            
            // If we deleted the primary image, set another one as primary
            if ($isPrimary && $imageCount > 1) {
                $newPrimary = ApartmentImage::where('apartment_id', $apartment->id)->first();
                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                }
            }
            
            return back()->with('success', 'Image deleted successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting image: ' . $e->getMessage());
        }
    }
}