<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Apartment;
use App\Models\AvailableDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get all apartments with their owner information
        $apartments = Apartment::with('owner.user')->get();

        // Get distinct cities from apartments table
        $cities = DB::table('apartments')->select('city')->distinct()->get()->pluck('city');

        // Get price range for filters
        $minPrice = DB::table('apartments')->min('price_per_night');
        $maxPrice = DB::table('apartments')->max('price_per_night');

        return view('home', compact('apartments', 'cities', 'minPrice', 'maxPrice'));
    }

    /**
     * Search for apartments based on filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search(Request $request)
    {
        $query = Apartment::with(['owner.user', 'images']);

        // Apply filters if provided
        if ($request->filled('apartment_id')) {
            $query->where('id', $request->apartment_id);
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_night', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->filled('guests')) {
            $query->where('max_guests', '>=', $request->guests);
        }

        if ($request->filled('check_in') && $request->filled('check_out')) {
            $checkIn = $request->check_in;
            $checkOut = $request->check_out;
            
            // Filter apartments with available dates for the requested period
            $query->whereHas('availableDates', function ($q) use ($checkIn, $checkOut) {
                $q->where('start_date', '<=', $checkIn)
                  ->where('end_date', '>=', $checkOut)
                  ->where('is_booked', false);
            });
        }

        // Include amenities filters
        if ($request->filled('has_wifi')) {
            $query->where('has_wifi', true);
        }
        
        if ($request->filled('has_parking')) {
            $query->where('has_parking', true);
        }
        
        if ($request->filled('has_kitchen')) {
            $query->where('has_kitchen', true);
        }
        
        if ($request->filled('has_air_conditioning')) {
            $query->where('has_air_conditioning', true);
        }

        // Get only available apartments
        $query->where('status', 'available');

        $apartments = $query->get();
        
        // Get available dates for booking
        $availableDates = AvailableDate::where('is_booked', false)->get();

        return view('apartments', ['apartments' => $apartments, 'availableDates' => $availableDates]);
    }
}