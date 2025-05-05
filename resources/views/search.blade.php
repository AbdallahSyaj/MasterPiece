@extends('layouts.app')

@section('title', 'Search Results - medXpert')


@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Doctor Search Results</h1>
    
    <!-- Filter summary -->
    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <span class="font-medium text-blue-600">Filters:</span>
            
            @if(request('doctor_id'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    Doctor: {{ App\Models\Doctor::find(request('doctor_id'))->user->name ?? 'Selected Doctor' }}
                </span>
            @endif
            
            @if(request('specialty'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    Specialty: {{ request('specialty') }}
                </span>
            @endif
            
            @if(request('city'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    Location: {{ request('city') }}
                </span>
            @endif
            
            @if(request('min_price') || request('max_price'))
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    Price: 
                    @if(request('min_price'))
                        Min: {{ request('min_price') }} JD
                    @endif
                    @if(request('min_price') && request('max_price'))
                        -
                    @endif
                    @if(request('max_price'))
                        Max: {{ request('max_price') }} JD
                    @endif
                </span>
            @endif
            
            <a href="{{ route('home') }}" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm hover:bg-gray-300 transition ml-auto">
                <i class="fas fa-redo mr-1"></i> Reset Filters
            </a>
        </div>
    </div>
    
    <!-- Results count -->
    <p class="text-gray-600 mb-6">Found {{ $doctors->total() }} doctors matching your criteria</p>
    
    @if($doctors->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($doctors as $doctor)
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <!-- Doctor avatar - replace with actual image if available -->
                            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-md text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">Dr. {{ $doctor->user->name }}</h3>
                                <p class="text-blue-600">{{ $doctor->specialty }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-3">
                            <i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>
                            <span>{{ $doctor->city }}, Jordan</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <i class="fas fa-money-bill-wave text-blue-600 mr-2"></i>
                            <span>{{ $doctor->consultation_fee }} JD per consultation</span>
                        </div>
                        
                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span>{{ number_format($doctor->average_rating ?? 0, 1) }} • {{ $doctor->reviews_count ?? 0 }} reviews</span>
                        </div>
                        
                        <a href="{{ route('doctors.show', $doctor->id) }}" class="block w-full text-center py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            View Profile & Book
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $doctors->appends(request()->query())->links() }}
        </div>
    @else
        <div class="bg-white p-8 rounded-xl shadow text-center">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-search text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">No doctors found</h3>
            <p class="text-gray-600 mb-4">Try adjusting your search filters for more results.</p>
            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Reset Filters
            </a>
        </div>
    @endif
</div>
@endsection