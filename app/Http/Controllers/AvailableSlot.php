<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\admin\Doctor;
use App\Models\admin\Patient;
use App\Models\available_slots;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AvailableSlot extends Controller
{

    public function doctors(Request $request)
    {
        $query = Doctor::with(['user', 'doctorDetails']);

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', $request->name);
            });
        }


        if ($request->filled('specialty')) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('specialty', 'like', '%' . $request->specialty . '%');
            });
        }

        if ($request->filled('price')) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('price', '<=', $request->price);
            });
        }

        if ($request->filled('city') && in_array($request->city, ['Amman', 'Zarqa', 'Irbid'])) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $doctors = $query->get();
        $appointments = available_slots::where('is_booked', 0)->get();
        $specialties = DB::table('doctor_details')->select('specialty')->distinct()->get()->pluck('specialty');
        $cities = DB::table('doctor_details')->select('city')->distinct()->get()->pluck('city');

        return view('doctor', ['doctors' => $doctors, 'appointments' => $appointments, 'specialties' => $specialties, 'cities' => $cities]);
    }

    public function book(Request $request)
    {
        $request->validate([
            'slot_id' => 'required|exists:available_slots,id',
        ]);

        // Find the slot
        $slot = available_slots::findOrFail($request->slot_id);

        // Ensure the slot is available
        if ($slot->is_booked) {
            return back()->with('error', 'This slot is already booked.');
        }

        // Test Patient ID (Replace this with actual user authentication)
        $user = Auth::user();
        $testPatientId = $user->patient->id;

        // Move slot details to the Appointment table
        Appointment::create([
            'user_id' => $testPatientId,
            'doctor_id' => $slot->doctor_id,
            'slot_id' => $slot->id,
            'appointment_date' => $slot->date,
            'appointment_time' => $slot->start_time,
            'status' => 'pending',
        ]);

        // Mark the slot as booked
        $slot->update(['is_booked' => 1]);

        return back()->with('success', 'Appointment booked successfully!');
    }
}
