<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Owner;
use App\Models\Apartment;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if ($user->role === 'tenant') {
            $tenant = Tenant::where('user_id', $user->id)->first();
            $bookings = Booking::with('apartment')->where('tenant_id', $tenant?->id)->get();
            return view('profile.index', compact('user', 'tenant', 'bookings'));
        }

        if ($user->role === 'owner') {
            $owner = Owner::where('user_id', $user->id)->first();
            $apartments = Apartment::where('owner_id', $owner?->id)->get();
            return view('profile.index', compact('user', 'owner', 'apartments'));
        }

        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        DB::beginTransaction();

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            if ($user->role === 'tenant') {
                $tenant = Tenant::where('user_id', $user->id)->first();
                if ($tenant) {
                    $tenant->update([
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'age' => $request->age,
                        'gender' => $request->gender,
                    ]);
                }
            }

            if ($user->role === 'owner') {
                $owner = Owner::where('user_id', $user->id)->first();
                if ($owner) {
                    $owner->update([
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'bio' => $request->bio,
                        'profile_image' => $request->profile_image,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Profile update failed: ' . $e->getMessage()]);
        }
    }

    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile')->with('success', 'Password changed successfully.');
    }
}
