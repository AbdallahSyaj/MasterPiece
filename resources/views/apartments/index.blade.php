@extends('layouts.app')

@section('title', 'Available Apartments')

@section('content')
    <!-- Hero Section for Apartments -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 py-12 text-white">
        <div class="container mx-auto px-4">
            <h1 class="text-4xl font-bold mb-4">Find Your Perfect Stay</h1>
            <p class="text-xl mb-6">Explore our selection of quality apartments across various cities</p>
            
            <!-- Search Filter -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8 search-box">
                <form action="{{ route('apartments.index') }}" method="GET" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="city" class="block text-gray-700 mb-2">City</label>
                        <select name="city" id="city" class="w-full rounded-md border-gray-300 shadow-sm px-4 py-2 bg-gray-50 text-gray-800">
                            <option value="">All Cities</option>
                            <option value="Amman">Amman</option>
                            <option value="Zarqa">Zarqa</option>
                            <option value="Irbid">Irbid</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="check_in" class="block text-gray-700 mb-2">Check In</label>
                        <input type="date" name="check_in" id="check_in" class="w-full rounded-md border-gray-300 shadow-sm px-4 py-2 bg-gray-50 text-gray-800">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="check_out" class="block text-gray-700 mb-2">Check Out</label>
                        <input type="date" name="check_out" id="check_out" class="w-full rounded-md border-gray-300 shadow-sm px-4 py-2 bg-gray-50 text-gray-800">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label for="guests" class="block text-gray-700 mb-2">Guests</label>
                        <select name="guests" id="guests" class="w-full rounded-md border-gray-300 shadow-sm px-4 py-2 bg-gray-50 text-gray-800">
                            <option value="1">1 Guest</option>
                            <option value="2">2 Guests</option>
                            <option value="3">3 Guests</option>
                            <option value="4">4+ Guests</option>
                        </select>
                    </div>
                    <div class="flex items-end w-full md:w-auto">
                        <button type="submit" class="w-full md:w-auto bg-primary text-white font-semibold py-2 px-6 rounded-md hover:bg-primary-dark transition duration-300">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Apartments Listing -->
    <div class="container mx-auto px-4 py-12">
        <!-- Stats bar -->
        <div class="flex flex-wrap justify-between mb-8 gap-4">
            <div class="text-xl font-semibold text-gray-700">
                <span>{{ count($apartments) }} Properties Found</span>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-gray-700">Sort by:</span>
                    <select class="border rounded-md px-3 py-1 text-gray-700">
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
                        <option>Newest First</option>
                        <option>Top Rated</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Apartments Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($apartments as $apartment)
                <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300 feature-card">
                    <!-- Apartment Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($apartment->images->count() > 0)
                            <img src="{{ asset('storage/' . $apartment->images->where('is_primary', true)->first()->image_path ?? $apartment->images->first()->image_path) }}" 
                                 alt="{{ $apartment->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-300">
                                <i class="fas fa-home text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4 bg-white px-2 py-1 rounded-md shadow-sm">
                            <span class="text-primary font-bold">${{ number_format($apartment->price_per_night, 2) }}</span>
                            <span class="text-gray-600 text-sm">/ night</span>
                        </div>
                    </div>

                    <!-- Apartment Info -->
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-800">{{ $apartment->title }}</h3>
                            <div class="flex items-center">
                                @for($i = 0; $i < $apartment->rating; $i++)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @endfor
                                @for($i = $apartment->rating; $i < 5; $i++)
                                    <i class="far fa-star text-yellow-400"></i>
                                @endfor
                            </div>
                        </div>
                        
                        <p class="text-gray-600 mb-4">
                            <i class="fas fa-map-marker-alt mr-2 text-primary"></i>
                            {{ $apartment->city }}, {{ $apartment->address }}
                        </p>
                        
                        <div class="flex justify-between text-gray-700 mb-4">
                            <span><i class="fas fa-bed mr-1"></i> {{ $apartment->bedrooms }} Beds</span>
                            <span><i class="fas fa-bath mr-1"></i> {{ $apartment->bathrooms }} Baths</span>
                            <span><i class="fas fa-users mr-1"></i> {{ $apartment->max_guests }} Guests</span>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($apartment->has_wifi)
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-wifi mr-1"></i> WiFi
                                </span>
                            @endif
                            @if($apartment->has_parking)
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-parking mr-1"></i> Parking
                                </span>
                            @endif
                            @if($apartment->has_kitchen)
                                <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-utensils mr-1"></i> Kitchen
                                </span>
                            @endif
                            @if($apartment->has_air_conditioning)
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-snowflake mr-1"></i> AC
                                </span>
                            @endif
                        </div>
                        
                        <!-- Owner info -->
                        <div class="flex items-center mb-4">
                            <div class="h-10 w-10 rounded-full bg-gray-300 overflow-hidden mr-3">
                                @if($apartment->owner->profile_image)
                                    <img src="{{ asset('storage/' . $apartment->owner->profile_image) }}" 
                                        alt="{{ $apartment->owner->user->name }}" 
                                        class="h-full w-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full">
                                        <i class="fas fa-user text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-700">{{ $apartment->owner->user->name }}</p>
                                <p class="text-xs text-gray-500">Owner</p>
                            </div>
                        </div>

                        <!-- Available dates -->
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-700 mb-2">Available Dates:</h4>
                            <div class="space-y-1 max-h-24 overflow-y-auto">
                                @forelse($apartment->availableDates->where('is_booked', false)->take(3) as $date)
                                    <div class="flex items-center text-sm">
                                        <i class="far fa-calendar text-primary mr-2"></i>
                                        {{ \Carbon\Carbon::parse($date->start_date)->format('M d, Y') }} - 
                                        {{ \Carbon\Carbon::parse($date->end_date)->format('M d, Y') }}
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">No available dates at the moment</p>
                                @endforelse
                                @if($apartment->availableDates->where('is_booked', false)->count() > 3)
                                    <p class="text-xs text-primary font-medium">+ {{ $apartment->availableDates->where('is_booked', false)->count() - 3 }} more dates</p>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <a href="#" class="text-primary hover:underline font-medium">View Details</a>
                            <button class="bg-primary text-white px-4 py-2 rounded-md hover:bg-primary-dark transition duration-300">
                                Book Now
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 rounded-lg shadow text-center">
                    <i class="fas fa-home text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">No Apartments Found</h3>
                    <p class="text-gray-500">Try adjusting your search filters or check back later.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <nav class="flex items-center space-x-2">
                <a href="#" class="px-3 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-angle-left"></i>
                </a>
                <a href="#" class="px-3 py-1 rounded-md border border-gray-300 bg-primary text-white">1</a>
                <a href="#" class="px-3 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">2</a>
                <a href="#" class="px-3 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">3</a>
                <span class="px-3 py-1 text-gray-500">...</span>
                <a href="#" class="px-3 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">10</a>
                <a href="#" class="px-3 py-1 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-angle-right"></i>
                </a>
            </nav>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize date pickers with min date as today
        const today = new Date().toISOString().split('T')[0];
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        
        if (checkInInput && checkOutInput) {
            checkInInput.min = today;
            
            // Set checkout min date when checkin changes
            checkInInput.addEventListener('change', function() {
                checkOutInput.min = this.value;
                
                // If checkout date is before new checkin date, reset it
                if (checkOutInput.value && checkOutInput.value < this.value) {
                    checkOutInput.value = '';
                }
            });
        }
    });
</script>
@endpush