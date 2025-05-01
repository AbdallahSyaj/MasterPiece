<?php


// app/Http/Controllers/PropertyController.php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Routing\Controller;

class PropertyController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }

    public function index(Request $request)
    {
        $query = Property::where('is_available', true);
        
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }
        
        if ($request->has('beds')) {
            $query->where('bedrooms', '>=', $request->beds);
        }
        
        if ($request->has('baths')) {
            $query->where('bathrooms', '>=', $request->baths);
        }
        
        if ($request->has('min_price')) {
            $query->where('monthly_rent', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('monthly_rent', '<=', $request->max_price);
        }
        
        if ($request->has('property_type')) {
            $query->where('property_type', $request->property_type);
        }
        
        $properties = $query->with(['images', 'owner'])->paginate(12);
        
        return view('properties.index', compact('properties'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        
        $properties = Property::where('is_available', true)
            ->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('address', 'like', "%{$keyword}%")
                    ->orWhere('city', 'like', "%{$keyword}%");
            })
            ->with(['images', 'owner'])
            ->paginate(12);
        
        return view('properties.search', compact('properties', 'keyword'));
    }

    public function show(Property $property)
    {
        $property->load(['images', 'amenities', 'owner', 'reviews.reviewer']);
        $similarProperties = Property::where('city', $property->city)
            ->where('property_id', '!=', $property->property_id)
            ->where('is_available', true)
            ->take(3)
            ->get();
        
        return view('properties.show', compact('property', 'similarProperties'));
    }

    public function create()
    {
        $this->authorize('create', Property::class);
        
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Property::class);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'property_type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'required|string',
            'area_size' => 'required|numeric',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'monthly_rent' => 'required|numeric',
            'security_deposit' => 'required|numeric',
            'amenities' => 'nullable|array',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $property = new Property($validated);
        $property->owner_id = Auth::id();
        $property->is_available = true;
        $property->listed_date = now();
        $property->save();
        
        // Process images
        if ($request->hasFile('images')) {
            $displayOrder = 1;
            foreach ($request->file('images') as $image) {
                $path = $image->store('property-images', 'public');
                
                PropertyImage::create([
                    'property_id' => $property->property_id,
                    'image_url' => $path,
                    'display_order' => $displayOrder++,
                ]);
            }
        }
        
        // Process amenities
        if ($request->has('amenities')) {
            foreach ($request->amenities as $amenityName) {
                Amenity::create([
                    'property_id' => $property->property_id,
                    'amenity_name' => $amenityName,
                ]);
            }
        }
        
        return redirect()->route('properties.show', $property)
            ->with('success', 'العقار تم إضافته بنجاح');
    }

    public function edit(Property $property)
    {
        $this->authorize('update', $property);
        
        $property->load(['images', 'amenities']);
        
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'property_type' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'required|string',
            'area_size' => 'required|numeric',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'monthly_rent' => 'required|numeric',
            'security_deposit' => 'required|numeric',
            'is_available' => 'boolean',
            'amenities' => 'nullable|array',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        $property->update($validated);
        
        // Process new images
        if ($request->hasFile('new_images')) {
            $maxDisplayOrder = $property->images()->max('display_order') ?? 0;
            
            foreach ($request->file('new_images') as $image) {
                $path = $image->store('property-images', 'public');
                
                PropertyImage::create([
                    'property_id' => $property->property_id,
                    'image_url' => $path,
                    'display_order' => ++$maxDisplayOrder,
                ]);
            }
        }
        
        // Update amenities
        if ($request->has('amenities')) {
            // Remove old amenities
            $property->amenities()->delete();
            
            // Add new amenities
            foreach ($request->amenities as $amenityName) {
                Amenity::create([
                    'property_id' => $property->property_id,
                    'amenity_name' => $amenityName,
                ]);
            }
        }
        
        return redirect()->route('properties.show', $property)
            ->with('success', 'تم تحديث العقار بنجاح');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        
        // Check if property has active rentals
        if ($property->rentals()->where('rental_status', 'active')->exists()) {
            return back()->with('error', 'لا يمكن حذف العقار لأنه يحتوي على عقود إيجار نشطة');
        }
        
        // Delete property images from storage
        foreach ($property->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }
        
        $property->delete();
        
        return redirect()->route('properties.index')
            ->with('success', 'تم حذف العقار بنجاح');
    }

    public function myProperties()
    {
        $this->authorize('viewOwned', Property::class);
        /** @var \App\Models\User $user */
             $user = Auth::user();

$properties = $user->ownedProperties()
    ->with(['images'])
    ->paginate(10);
        
        return view('properties.my-properties', compact('properties'));
    }
}