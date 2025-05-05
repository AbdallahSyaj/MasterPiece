<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'apartment_id',
        'start_date',
        'end_date',
        'is_booked',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_booked' => 'boolean',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class, 'available_date_id');
    }
}