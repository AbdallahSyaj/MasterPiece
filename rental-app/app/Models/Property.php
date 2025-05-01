<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'property_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'property_type',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'area_size',
        'bedrooms',
        'bathrooms',
        'monthly_rent',
        'security_deposit',
        'is_available',
        'listed_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'area_size' => 'decimal:2',
        'monthly_rent' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'is_available' => 'boolean',
        'listed_date' => 'date',
    ];

    /**
     * Get the owner of the property.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_id');
    }

    /**
     * Get the images for the property.
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id', 'property_id');
    }

    /**
     * Get the amenities for the property.
     */
    public function amenities()
    {
        return $this->hasMany(Amenity::class, 'property_id', 'property_id');
    }

    /**
     * Get the rentals for the property.
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'property_id', 'property_id');
    }

    /**
     * Get the active rental for the property.
     */
    public function activeRental()
    {
        return $this->rentals()->where('rental_status', 'active')->first();
    }

    /**
     * Get the reviews for the property.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'property_id', 'property_id');
    }

    /**
     * Get the average rating for the property.
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}

