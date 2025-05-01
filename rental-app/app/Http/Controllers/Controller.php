<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /*
 



// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $pendingPayments = Payment::with(['rental.property', 'rental.tenant'])
                ->where('payment_status', 'pending')
                ->orderBy('due_date')
                ->paginate(15, ['*'], 'pending_page');
            
            $paidPayments = Payment::with(['rental.property', 'rental.tenant'])
                ->where('payment_status', 'paid')
                ->orderBy('payment_date', 'desc')
                ->paginate(15, ['*'], 'paid_page');
            
            $overduePayments = Payment::with(['rental.property', 'rental.tenant'])
                ->where('payment_status', 'overdue')
                ->orderBy('due_date')
                ->paginate(15, ['*'], 'overdue_page');
        } elseif ($user->isOwner()) {
            $pendingPayments = Payment::whereHas('rental.property', function ($q) use ($user) {
                $q->where('owner_id', $user->user_id);
            })->with(['rental.property', 'rental.tenant'])
              ->where('payment_status', 'pending')
              ->orderBy('due_date')
              ->paginate(15, ['*'], 'pending_page');
            
            $paidPayments = Payment::whereHas('rental.property', function ($q) use ($user) {
                $q->where('owner_id', $user->user_id);
            })->with(['rental.property', 'rental.tenant'])
              ->where('payment_status', 'paid')
              ->orderBy('payment_date', 'desc')
              ->paginate(15, ['*'], 'paid_page');
            
            $overduePayments = Payment::whereHas('rental.property', function ($q) use ($user) {
                $q->where('owner_id', $user->user_id);
            })->with(['rental.property', 'rental.tenant'])
              ->where('payment_status', 'overdue')
              ->orderBy('due_date')
              ->paginate(15, ['*'], 'overdue_page');
        } else {
            $pendingPayments = Payment::whereHas('rental', function ($q) use ($user) {
                $q->where('tenant_id', $user->user_id);
            })->with('rental.property')
              ->where('payment_status', 'pending')
              ->orderBy('due_date')
              ->paginate(15, ['*'], 'pending_page');
            
            $paidPayments = Payment::whereHas('rental', function ($q) use ($user) {
                $q->where('tenant_id', $user->user_id);
            })->with('rental.property')
              ->where('payment_status', 'paid')
              ->orderBy('payment_date', 'desc')
              ->paginate(15, ['*'], 'paid_page');
            
            $overduePayments = Payment::whereHas('rental', function ($q) use ($user) {
                $q->where('tenant_id', $user->user_id);
            })->with('rental.property')
              ->where('payment_status', 'overdue')
              ->orderBy('due_date')
              ->paginate(15, ['*'], 'overdue_page');
        }
        
        return view('payments.index', compact('pendingPayments', 'paidPayments', 'overduePayments'));
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        
        $payment->load(['rental.property.owner', 'rental.tenant']);
        
        return view('payments.show', compact('payment'));
    }

    public function makePayment(Payment $payment)
    {
        $this->authorize('pay', $payment);
        
        if ($payment->payment_status !== 'pending' && $payment->payment_status !== 'overdue') {
            return redirect()->back()->with('error', 'لا يمكن دفع هذه الدفعة');
        }
        
        return view('payments.make', compact('payment'));
    }

    public function storePayment(Request $request, Payment $payment)
    {
        $this->authorize('pay', $payment);
        
        if ($payment->payment_status !== 'pending' && $payment->payment_status !== 'overdue') {
            return redirect()->back()->with('error', 'لا يمكن دفع هذه الدفعة');
        }
        
        $validated = $request->validate([
            'payment_method' => 'require


    */
}
