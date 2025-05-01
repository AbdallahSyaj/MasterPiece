<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'payment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rental_id',
        'amount',
        'due_date',
        'payment_date',
        'payment_method',
        'payment_status',
        'transaction_id',
        'receipt_document',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date',
    ];

    /**
     * Get the rental associated with the payment.
     */
    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id', 'rental_id');
    }

    /**
     * Get the tenant who made the payment.
     */
    public function tenant()
    {
        return $this->rental->tenant();
    }

    /**
     * Get the property owner who received the payment.
     */
    public function owner()
    {
        return $this->rental->owner();
    }

    /**
     * Check if payment is overdue.
     */
    public function isOverdue()
    {
        return $this->payment_status === 'overdue' || 
               ($this->payment_status === 'pending' && $this->due_date < now());
    }

    /**
     * Check if payment is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
