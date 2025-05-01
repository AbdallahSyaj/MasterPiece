<?php



// app/Http/Controllers/RentalController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class RentalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var \App\Models\User $user */

        $user = Auth::user();
        //0796845335
        //0795666070
        
        if ($user->isAdmin()) {
            $rentals = Rental::with(['property', 'tenant'])->paginate(15);
        } elseif ($user->isOwner()) {
            $rentals = Rental::whereHas('property', function ($q) use ($user) {
                $q->where('owner_id', $user->user_id);
            })->with(['property', 'tenant'])->paginate(15);
        } else {
            $rentals = $user->rentals()->with('property')->paginate(15);
        }
        
        return view('rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        $this->authorize('view', $rental);
        
        $rental->load(['property.owner', 'tenant', 'payments']);
        
        return view('rentals.show', compact('rental'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Rental::class);
        
        $property = Property::findOrFail($request->property_id);
        
        // Check if property is available
        if (!$property->is_available) {
            return redirect()->back()->with('error', 'هذا العقار غير متاح للإيجار حالياً');
        }
        
        return view('rentals.create', compact('property'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Rental::class);
        
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,property_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'rent_due_day' => 'required|integer|min:1|max:28',
        ]);
        
        $property = Property::findOrFail($validated['property_id']);
        
        // Check if property is available
        if (!$property->is_available) {
            return redirect()->back()->with('error', 'هذا العقار غير متاح للإيجار حالياً');
        }
        
        DB::beginTransaction();
        
        try {
            // Create rental
            $rental = new Rental([
                'property_id' => $property->property_id,
                'tenant_id' => Auth::id(),
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'monthly_rent' => $property->monthly_rent,
                'rent_due_day' => $validated['rent_due_day'],
                'rental_status' => 'pending',
            ]);
            
            $rental->save();
            
            // Create initial payment for security deposit
            Payment::create([
                'rental_id' => $rental->rental_id,
                'amount' => $property->security_deposit,
                'due_date' => now()->addDays(3),
                'payment_status' => 'pending',
            ]);
            
            // Create monthly payments
            $startDate = new \DateTime($validated['start_date']);
            $endDate = new \DateTime($validated['end_date']);
            
            $currentDate = clone $startDate;
            $dueDay = $validated['rent_due_day'];
            
            while ($currentDate <= $endDate) {
                // Set the day to the rent due day
                $currentDate->setDate(
                    $currentDate->format('Y'),
                    $currentDate->format('m'),
                    min($dueDay, $currentDate->format('t'))
                );
                
                // Skip if due date is before start date
                if ($currentDate < $startDate) {
                    $currentDate->modify('+1 month');
                    continue;
                }
                
                Payment::create([
                    'rental_id' => $rental->rental_id,
                    'amount' => $property->monthly_rent,
                    'due_date' => $currentDate->format('Y-m-d'),
                    'payment_status' => 'pending',
                ]);
                
                $currentDate->modify('+1 month');
            }
            
            // Create notification for property owner
            Notification::create([
                'user_id' => $property->owner_id,
                'message' => "لديك طلب استئجار جديد للعقار: {$property->title}",
                'notification_type' => 'rental_request',
                'is_read' => false,
            ]);
            
            DB::commit();
            
            return redirect()->route('rentals.show', $rental)
                ->with('success', 'تم إرسال طلب الإيجار بنجاح، انتظر موافقة المالك');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء طلب الإيجار');
        }
    }

    public function approve(Rental $rental)
    {
        $this->authorize('approve', $rental);
        
        if ($rental->rental_status !== 'pending') {
            return redirect()->back()->with('error', 'لا يمكن الموافقة على هذا الطلب');
        }
        
        $property = $rental->property;
        
        DB::beginTransaction();
        
        try {
            // Update rental status
            $rental->rental_status = 'active';
            $rental->save();
            
            // Update property availability
            $property->is_available = false;
            $property->save();
            
            // Create notification for tenant
            Notification::create([
                'user_id' => $rental->tenant_id,
                'message' => "تمت الموافقة على طلب استئجارك للعقار: {$property->title}",
                'notification_type' => 'rental_approved',
                'is_read' => false,
            ]);
            
            DB::commit();
            
            return redirect()->route('rentals.show', $rental)
                ->with('success', 'تمت الموافقة على طلب الإيجار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء الموافقة على طلب الإيجار');
        }
    }

    public function reject(Rental $rental)
    {
        $this->authorize('reject', $rental);
        
        if ($rental->rental_status !== 'pending') {
            return redirect()->back()->with('error', 'لا يمكن رفض هذا الطلب');
        }
        
        $property = $rental->property;
        
        DB::beginTransaction();
        
        try {
            // Update rental status
            $rental->rental_status = 'cancelled';
            $rental->save();
            
            // Delete pending payments
            $rental->payments()->where('payment_status', 'pending')->delete();
            
            // Create notification for tenant
            Notification::create([
                'user_id' => $rental->tenant_id,
                'message' => "تم رفض طلب استئجارك للعقار: {$property->title}",
                'notification_type' => 'rental_rejected',
                'is_read' => false,
            ]);
            
            DB::commit();
            
            return redirect()->route('rentals.index')
                ->with('success', 'تم رفض طلب الإيجار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء رفض طلب الإيجار');
        }
    }

    public function terminate(Request $request, Rental $rental)
    {
        $this->authorize('terminate', $rental);
        
        if ($rental->rental_status !== 'active') {
            return redirect()->back()->with('error', 'لا يمكن إنهاء هذا العقد');
        }
        
        $validated = $request->validate([
            'termination_reason' => 'required|string',
        ]);
        
        $property = $rental->property;
        
        DB::beginTransaction();
        
        try {
            // Update rental status
            $rental->rental_status = 'completed';
            $rental->save();
            
            // Delete future pending payments
            $rental->payments()
                ->where('payment_status', 'pending')
                ->where('due_date', '>', now())
                ->delete();
            
            // Update property availability
            $property->is_available = true;
            $property->save();
            
            // Create notification for the other party
            $notifyUserId = Auth::id() === $rental->tenant_id ? $property->owner_id : $rental->tenant_id;
            $notifierType = Auth::id() === $rental->tenant_id ? 'المستأجر' : 'المالك';
            
            Notification::create([
                'user_id' => $notifyUserId,
                'message' => "تم إنهاء عقد الإيجار للعقار: {$property->title} من قبل {$notifierType}. السبب: {$validated['termination_reason']}",
                'notification_type' => 'rental_terminated',
                'is_read' => false,
            ]);
            
            DB::commit();
            
            return redirect()->route('rentals.index')
                ->with('success', 'تم إنهاء عقد الإيجار بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنهاء عقد الإيجار');
        }
    }
}
