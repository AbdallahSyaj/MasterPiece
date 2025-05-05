<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'address',
        'city',
        'price_per_night',
        'bedrooms',
        'bathrooms',
        'max_guests',
        'has_wifi',
        'has_parking',
        'has_kitchen',
        'has_air_conditioning',
        'status',
        'rating',
    ];

    protected $casts = [
        'has_wifi' => 'boolean',
        'has_parking' => 'boolean',
        'has_kitchen' => 'boolean',
        'has_air_conditioning' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function images()
    {
        return $this->hasMany(ApartmentImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ApartmentImage::class)->where('is_primary', true);
    }

    public function availableDates()
    {
        return $this->hasMany(AvailableDate::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function getAverageRating()
    {
        return $this->reviews()->avg('rating') ?? $this->rating;
    }
}