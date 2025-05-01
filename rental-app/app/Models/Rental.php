<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rental_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'property_id',
        'tenant_id',
        'start_date',
        'end_date',
        'monthly_rent',
        'rent_due_day',
        'rental_status',
        'contract_document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'monthly_rent' => 'decimal:2',
    ];

    /**
     * Get the property being rented.
     */
    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id', 'property_id');
    }

    /**
     * Get the tenant who is renting.
     */
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id', 'user_id');
    }

    /**
     * Get the property owner through the property.
     */
    public function owner()
    {
        return $this->property->owner();
    }

    /**
     * Get the payments for this rental.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'rental_id', 'rental_id');
    }

    /**
     * Get pending payments.
     */
    public function pendingPayments()
    {
        return $this->payments()->where('payment_status', 'pending');
    }

    /**
     * Get paid payments.
     */
    public function paidPayments()
    {
        return $this->payments()->where('payment_status', 'paid');
    }

    /**
     * Get overdue payments.
     */
    public function overduePayments()
    {
        return $this->payments()->where('payment_status', 'overdue');
    }

    /**
     * Check if rental is active.
     */
    public function isActive()
    {
        return $this->rental_status === 'active';
    }
}

