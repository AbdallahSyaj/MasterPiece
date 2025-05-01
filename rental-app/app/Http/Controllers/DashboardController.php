<?php

// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isOwner()) {
            return $this->ownerDashboard();
        } elseif ($user->isTenant()) {
            return $this->tenantDashboard();
        }

        return redirect()->route('home');
    }

    private function adminDashboard()
    {
        $usersCount = User::count();
        $ownersCount = User::where('role', 'owner')->count();
        $tenantsCount = User::where('role', 'tenant')->count();
        $propertiesCount = Property::count();
        $activeRentalsCount = Rental::where('rental_status', 'active')->count();
        $pendingVerificationsCount = User::where('is_verified', false)->count();

        $latestUsers = User::latest()->take(5)->get();
        $latestProperties = Property::with('owner')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'usersCount', 
            'ownersCount', 
            'tenantsCount', 
            'propertiesCount', 
            'activeRentalsCount', 
            'pendingVerificationsCount', 
            'latestUsers', 
            'latestProperties'
        ));
    }

    private function ownerDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $propertiesCount = $user->ownedProperties()->count();
        $availablePropertiesCount = $user->ownedProperties()->where('is_available', true)->count();
        $rentedPropertiesCount = $user->ownedProperties()->whereHas('rentals', function ($q) {
            $q->where('rental_status', 'active');
        })->count();
        
        $activeRentals = Rental::whereHas('property', function ($q) use ($user) {
            $q->where('owner_id', $user->user_id);
        })->where('rental_status', 'active')->with(['property', 'tenant'])->get();
        
        $upcomingPayments = Payment::whereHas('rental', function ($q) use ($user) {
            $q->whereHas('property', function ($q2) use ($user) {
                $q2->where('owner_id', $user->user_id);
            });
        })->where('payment_status', 'pending')
          ->where('due_date', '>=', now())
          ->where('due_date', '<=', now()->addDays(7))
          ->with(['rental.property', 'rental.tenant'])
          ->get();
        
        $latestProperties = $user->ownedProperties()->latest()->take(5)->get();
        
        $monthlyIncome = Payment::whereHas('rental', function ($q) use ($user) {
            $q->whereHas('property', function ($q2) use ($user) {
                $q2->where('owner_id', $user->user_id);
            });
        })->where('payment_status', 'paid')
          ->whereYear('payment_date', now()->year)
          ->whereMonth('payment_date', now()->month)
          ->sum('amount');

        return view('owner.dashboard', compact(
            'propertiesCount', 
            'availablePropertiesCount', 
            'rentedPropertiesCount', 
            'activeRentals', 
            'upcomingPayments', 
            'latestProperties', 
            'monthlyIncome'
        ));
    }

    private function tenantDashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $activeRentals = $user->rentals()
            ->where('rental_status', 'active')
            ->with('property')
            ->get();
        
        $upcomingPayments = Payment::whereHas('rental', function ($q) use ($user) {
            $q->where('tenant_id', $user->user_id);
        })->where('payment_status', 'pending')
          ->orderBy('due_date')
          ->with('rental.property')
          ->get();
        
        $overduePayments = Payment::whereHas('rental', function ($q) use ($user) {
            $q->where('tenant_id', $user->user_id);
        })->where('payment_status', 'overdue')
          ->orderBy('due_date')
          ->with('rental.property')
          ->get();
        
        $rentalHistory = $user->rentals()
            ->where('rental_status', '!=', 'active')
            ->with('property')
            ->get();
        
        return view('tenant.dashboard', compact(
            'activeRentals', 
            'upcomingPayments', 
            'overduePayments', 
            'rentalHistory'
        ));
    }
}
