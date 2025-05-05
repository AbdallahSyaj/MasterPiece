<?php

namespace App\Http\Controllers;

use App\Models\admin\Doctor;
use App\Models\AvailableSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\admin\DoctorDetails;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
// use Illuminate\Foundation\Auth\User;

{
    /**
     * Display the doctor's profile page.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Find doctor with relationships
        $doctor = Doctor::with(['user', 'doctorDetails'])
            ->findOrFail($id);
        // Auth::user()->id == $doctor->user_id;
        // $user = Auth::user();
        // $doctor = $user->doctor->with(['user', 'doctorDetails'])->findOrFail($id);


        // Get available slots for the next 7 days that are not booked
        $availableSlots = AvailableSlot::where('doctor_id', $id)
            ->where('date', '>=', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays(7))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('profile', [
            'doctor' => $doctor,

            'availableSlots' => $availableSlots,
        ]);
    }
    public function search(Request $request)
    {
        // Query the database based on the form fields
        $query = Doctor::query();

        // Search by doctor name (if provided)
        if ($request->has('doctor_id') && $request->doctor_id) {
            $query->where('doctors.id', $request->doctor_id);
        }

        // Search by specialty (if provided)
        if ($request->has('specialty') && $request->specialty) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('specialty', $request->specialty);
            });
        }

        // Search by city (if provided)
        if ($request->has('city') && $request->city) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        // Filter by price range (if provided)
        if ($request->has('min_price') && $request->min_price) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('price', '>=', $request->min_price);
            });
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('doctorDetails', function ($q) use ($request) {
                $q->where('price', '<=', $request->max_price);
            });
        }

        // Eager load the doctor details with the doctor data
        $doctors = $query->get();
        $appointments = AvailableSlot::where('is_booked', 0)->get();
        $specialties = DB::table('doctor_details')->select('specialty')->distinct()->get()->pluck('specialty');
        $cities = DB::table('doctor_details')->select('city')->distinct()->get()->pluck('city');


        return view('doctor', ['doctors' => $doctors, 'appointments' => $appointments, 'specialties' => $specialties, 'cities' => $cities,]);
    }
}
