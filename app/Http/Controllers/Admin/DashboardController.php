<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Models\Apartment;
use App\Models\ApartmentImage;
use App\Models\Tenant;
use App\Models\Booking;
use App\Models\AvailableDate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Form;
use App\Mail\MessageReply;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function owners()
    {
        $owners = Owner::with(['user', 'apartments'])->get();
        return view('admindashboard.owners.index', compact('owners'));
    }

    public function tenants()
    {
        $tenants = Tenant::with(['user', 'bookings'])->get();
        return view('admindashboard.tenants.index', compact('tenants'));
    }

    /**
     * Display a listing of all users.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function allUsers()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admindashboard.users.allusers', compact('users'));
    }

    /**
     * Show form to create a new user
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function createUser()
    {
        return view('admindashboard.users.create');
    }

    /**
     * Store a newly created user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,owner,tenant',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // If user is a tenant, create a tenant record
            if ($request->role == 'tenant') {
                Tenant::create([
                    'user_id' => $user->id,
                    'gender' => $request->gender ?? 'other',
                    'age' => $request->age ?? 0,
                    'phone' => $request->phone ?? '',
                    'address' => $request->address ?? '',
                ]);
            }

            // If user is an owner, create an owner record
            if ($request->role == 'owner') {
                Owner::create([
                    'user_id' => $user->id,
                    'phone' => $request->phone ?? '',
                    'address' => $request->address ?? '',
                    'verification_status' => 'pending',
                ]);
            }

            DB::commit();

            return redirect()->route('users')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified tenant.
     * 
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function editTenant($id)
    {
        $tenant = Tenant::with(['user', 'bookings'])->findOrFail($id);
        $apartments = Apartment::with('owner.user')->get();
        $bookings = Booking::where('tenant_id', $id)
            ->with('apartment')
            ->orderBy('check_in_date', 'desc')
            ->get();
        
        return view('admindashboard.tenants.edittenant', compact('tenant', 'apartments', 'bookings'));
    }

    /**
     * Update the specified tenant in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTenant(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user_id,
            'role' => 'required|in:admin,owner,tenant',
            'age' => 'required|integer|min:0|max:120',
            'gender' => 'required|in:male,female,other',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $tenant = Tenant::findOrFail($id);
            
            $tenant->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
            
            $tenant->update([
                'age' => $request->age,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
            
            DB::commit();
            
            return redirect()->route('tenants')
                ->with('success', 'Tenant information updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Error updating tenant information: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified tenant from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyTenant($id)
    {
        DB::beginTransaction();
        
        try {
            $tenant = Tenant::findOrFail($id);
            $userId = $tenant->user_id;
            
            $tenant->delete();
            
            User::destroy($userId);
            
            DB::commit();
            
            return redirect()->route('tenants')
                ->with('success', 'Tenant and all associated records have been deleted');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Error deleting tenant: ' . $e->getMessage());
        }
    }
    
    /**
     * Store a booking for a tenant
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBooking(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'apartment_id' => 'required|exists:apartments,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'special_requests' => 'nullable|string',
        ]);
    
        try {
            // Find or create an available date entry
            $availableDate = AvailableDate::create([
                'apartment_id' => $request->apartment_id,
                'start_date' => $request->check_in_date,
                'end_date' => $request->check_out_date,
                'is_booked' => true,
            ]);

            Booking::create([
                'tenant_id' => $request->tenant_id,  
                'apartment_id' => $request->apartment_id,
                'available_date_id' => $availableDate->id,
                'check_in_date' => $request->check_in_date,  
                'check_out_date' => $request->check_out_date,
                'number_of_guests' => $request->number_of_guests,
                'total_price' => $request->total_price,
                'status' => $request->status,
                'special_requests' => $request->special_requests,
            ]);
    
            return back()->with('success', 'Booking created successfully');
    
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating booking: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified booking from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            // Mark available dates as not booked
            if ($booking->availableDate) {
                $booking->availableDate->update(['is_booked' => false]);
            }
            
            $booking->delete();
            
            return back()->with('success', 'Booking deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting booking: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified owner.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function editOwner($id)
    {
        $owner = Owner::with(['user', 'apartments'])->findOrFail($id);
        $tenants = Tenant::with('user')->get();
        $apartments = Apartment::where('owner_id', $id)
            ->with('availableDates')
            ->get();
        
        return view('admindashboard.owners.editowner', compact('owner', 'tenants', 'apartments'));
    }

    /**
     * Update the specified owner in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateOwner(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $request->user_id,
            'role' => 'required|in:admin,owner,tenant',
            'phone' => 'required|string|max:15',
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'verification_status' => 'required|in:pending,verified,rejected',
        ]);

        DB::beginTransaction();

        try {
            $owner = Owner::findOrFail($id);
            
            $owner->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
            
            // Handle image upload if provided
            if ($request->hasFile('profile_image')) {
                $imageName = time() . '.' . $request->profile_image->extension();
                $request->profile_image->storeAs('public/owners', $imageName);
                
                // Delete old image if exists
                if ($owner->profile_image) {
                    Storage::delete('public/owners/' . $owner->profile_image);
                }
            } else {
                $imageName = $owner->profile_image;
            }
            
            $owner->update([
                'phone' => $request->phone,
                'address' => $request->address,
                'profile_image' => $imageName,
                'bio' => $request->bio,
                'verification_status' => $request->verification_status,
            ]);
            
            DB::commit();
            
            return redirect()->route('owners')
                ->with('success', 'Owner information updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Error updating owner information: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified owner from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyOwner($id)
    {
        DB::beginTransaction();
        
        try {
            $owner = Owner::findOrFail($id);
            $userId = $owner->user_id;
            
            // Delete profile image if exists
            if ($owner->profile_image) {
                Storage::disk('public')->delete('owners/' . $owner->profile_image);
            }
            
            // Handle apartment images before deleting apartments
            $apartments = Apartment::where('owner_id', $id)->get();
            foreach ($apartments as $apartment) {
                $images = ApartmentImage::where('apartment_id', $apartment->id)->get();
                foreach ($images as $image) {
                    Storage::disk('public')->delete('apartments/' . $image->image_path);
                    $image->delete();
                }
            }
            
            $owner->delete();
            User::destroy($userId);
            
            DB::commit();
            
            return redirect()->route('owners')
                ->with('success', 'Owner and all associated records have been deleted');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Error deleting owner: ' . $e->getMessage());
        }
    }

    /**
     * Store a new available date range for an apartment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAvailableDate(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'is_booked' => 'required|boolean'
        ]);
        
        AvailableDate::create([
            'apartment_id' => $request->apartment_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_booked' => $request->is_booked
        ]);
        
        return redirect()->back()->with('success', 'New available date range added successfully!');
    }

    /**
     * Show form to edit an available date range.
     * 
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function editAvailableDate($id)
    {
        $availableDate = AvailableDate::findOrFail($id);
        return view('admindashboard.availabledates.edit', compact('availableDate'));
    }

    /**
     * Update an available date range.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAvailableDate(Request $request, $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_booked' => 'required|boolean'
        ]);
        
        $availableDate = AvailableDate::findOrFail($id);
        $availableDate->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_booked' => $request->is_booked
        ]);
        
        $apartmentId = $availableDate->apartment_id;
        $ownerId = Apartment::findOrFail($apartmentId)->owner_id;
        
        return redirect()->route('admin.owners.edit', $ownerId)
            ->with('success', 'Available date range updated successfully!');
    }

    /**
     * Delete an available date range.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyAvailableDate($id)
    {
        $availableDate = AvailableDate::findOrFail($id);
        $apartmentId = $availableDate->apartment_id;
        $ownerId = Apartment::findOrFail($apartmentId)->owner_id;
        
        $availableDate->delete();
        
        return redirect()->route('admin.owners.edit', $ownerId)
            ->with('success', 'Available date range deleted successfully!');
    }

    /**
     * Display all messages received through contact forms.
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function messages()
    {
        // Get all messages with pagination
        $messages = Form::latest()->paginate(10);
        
        return view('admindashboard.messages', compact('messages'));
    }

    /**
     * Reply to a message.
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replyMessage(Request $request, $id)
    {
        $message = Form::findOrFail($id);
        
        // Validate request
        $validated = $request->validate([
            'reply' => 'required|string',
        ]);
        
        // Send email
        Mail::to($message->email)->send(new MessageReply($message, $validated['reply']));
        
        // Mark message as replied in database
        $message->update(['replied' => true]);
        
        return redirect()->route('admin.messages')->with('success', 'Reply sent successfully');
    }
    
    /**
     * Get unreplied messages for notifications.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnrepliedMessages()
    {
        $unrepliedMessages = Form::where('replied', false)
                                ->latest()
                                ->take(6)
                                ->get();

        $unrepliedCount = Form::where('replied', false)->count();

        return response()->json([
            'messages' => $unrepliedMessages,
            'count' => $unrepliedCount,
        ]);
    }

    /**
     * Show all apartments
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function apartments()
    {
        $apartments = Apartment::with(['owner.user', 'images'])->get();
        return view('admindashboard.apartments.index', compact('apartments'));
    }

    /**
     * Show the form for editing the specified apartment.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function editApartment($id)
    {
        $apartment = Apartment::with(['owner.user', 'images', 'availableDates'])->findOrFail($id);
        $owners = Owner::with('user')->get();
        
        return view('admindashboard.apartments.edit', compact('apartment', 'owners'));
    }

    /**
     * Update the specified apartment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateApartment(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'price_per_night' => 'required|numeric|min:0',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'max_guests' => 'required|integer|min:1',
            'has_wifi' => 'boolean',
            'has_parking' => 'boolean',
            'has_kitchen' => 'boolean',
            'has_air_conditioning' => 'boolean',
            'status' => 'required|in:available,unavailable,maintenance',
            'rating' => 'required|in:1,2,3,4,5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_image' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            $apartment = Apartment::findOrFail($id);
            
            $apartment->update([
                'title' => $request->title,
                'description' => $request->description,
                'address' => $request->address,
                'city' => $request->city,
                'price_per_night' => $request->price_per_night,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'max_guests' => $request->max_guests,
                'has_wifi' => $request->has_wifi ?? false,
                'has_parking' => $request->has_parking ?? false,
                'has_kitchen' => $request->has_kitchen ?? false,
                'has_air_conditioning' => $request->has_air_conditioning ?? false,
                'status' => $request->status,
                'rating' => $request->rating,
            ]);
            
            // Handle apartment images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $imageName = time() . '_' . rand(1000, 9999) . '.' . $imageFile->extension();
                    $imageFile->storeAs('public/apartments', $imageName);
                    
                    $apartmentImage = ApartmentImage::create([
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
            
            return redirect()->route('apartments')
                ->with('success', 'Apartment information updated successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()->with('error', 'Error updating apartment information: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified apartment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyApartment($id)
    {
        DB::beginTransaction();
        
        try {
            $apartment = Apartment::findOrFail($id);
            
            // Delete all images first
            $images = ApartmentImage::where('apartment_id', $apartment->id)->get();
            foreach ($images as $image) {
                Storage::disk('public')->delete('apartments/' . $image->image_path);
                $image->delete();
            }
            
            $apartment->delete();
            
            DB::commit();
            
            return redirect()->route('apartments')
                ->with('success', 'Apartment and all associated records have been deleted');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Error deleting apartment: ' . $e->getMessage());
        }
    }

    /**
     * Remove an apartment image.
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyApartmentImage($id)
    {
        try {
            $image = ApartmentImage::findOrFail($id);
            $apartmentId = $image->apartment_id;
            
            // Delete image file
            Storage::disk('public')->delete('apartments/' . $image->image_path);
            
            // Delete record
            $image->delete();
            
            return redirect()->route('admin.apartments.edit', $apartmentId)
                ->with('success', 'Image deleted successfully');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting image: ' . $e->getMessage());
        }
    }
}