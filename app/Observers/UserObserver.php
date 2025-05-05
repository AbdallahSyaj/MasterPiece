<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Owner;
use App\Models\Tenant;
use App\Models\Admin;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role === 'owner') {
            Owner::create([
                'user_id' => $user->id,
                'phone' => '',
                'verification_status' => 'pending'
            ]);
        } elseif ($user->role === 'tenant') {
            Tenant::create([
                'user_id' => $user->id, 
                'age' => 0, 
                'gender' => 'other'
            ]);
        } elseif ($user->role === 'admin') {
            Admin::create(['user_id' => $user->id]);
        }
    }

    public function updated(User $user): void
    {
        if ($user->isDirty('role')) {
            // Handle role changes and cleanup
            if ($user->getOriginal('role') === 'owner') {
                Owner::where('user_id', $user->id)->delete();
            } elseif ($user->getOriginal('role') === 'tenant') {
                Tenant::where('user_id', $user->id)->delete();
            } elseif ($user->getOriginal('role') === 'admin') {
                Admin::where('user_id', $user->id)->delete();
            }

            // Create new role-specific records
            if ($user->role === 'owner') {
                Owner::create([
                    'user_id' => $user->id,
                    'phone' => '',
                    'verification_status' => 'pending'
                ]);
            } elseif ($user->role === 'tenant') {
                Tenant::create([
                    'user_id' => $user->id, 
                    'age' => 0, 
                    'gender' => 'other'
                ]);
            } elseif ($user->role === 'admin') {
                Admin::create(['user_id' => $user->id]);
            }
        }
    }

    public function deleted(User $user): void
    {
        // When deleting a user, clean up all associated records
        Owner::where('user_id', $user->id)->delete();
        Tenant::where('user_id', $user->id)->delete();
        Admin::where('user_id', $user->id)->delete();
        
        // Note: Apartment listings and bookings would typically be handled separately
        // or with cascade deletes defined in the database migration
    }

    public function restored(User $user): void
    {
        // If you implement soft deletes and restoration logic
    }

    public function forceDeleted(User $user): void
    {
        // Similar to deleted, but for force delete operations
    }
}