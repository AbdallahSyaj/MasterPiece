@extends('layouts.app')

@section('title', 'HomeRent - Find Your Perfect Apartment')

@section('content')

<!-- Hero Section - Enhanced with consistent blue colors -->
<section class="hero-section flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-blue-700 to-blue-500 opacity-90"></div>
    <div class="container mx-auto px-4 text-center text-white relative z-10">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">Find Your Apartment & Book Your Stay</h1>
        <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto">The easiest way to connect with the best rental properties in Jordan</p>

        <!-- Search Form with Working Filters -->
        <form action="{{ route('apartments.index') }}" method="GET" class="search-box p-6 md:p-8 max-w-5xl mx-auto transform transition-all duration-300 hover:shadow-xl">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-7 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Apartment Name</label>
                    <div class="relative">
                        <select name="apartment_id" class="text-gray-700 w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none transition">
                            <option class="text-gray-700" value="">All Apartments</option>
                            @foreach($apartments as $apartment)
                            <option class="text-gray-700" value="{{ $apartment->id }}">{{ $apartment->title }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Bedrooms</label>
                    <div class="relative">
                        <select name="bedrooms" class="text-gray-700 w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none transition">
                            <option class="text-gray-700" value="">Any</option>
                            <option class="text-gray-700" value="1">1+</option>
                            <option class="text-gray-700" value="2">2+</option>
                            <option class="text-gray-700" value="3">3+</option>
                            <option class="text-gray-700" value="4">4+</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Location</label>
                    <div class="relative">
                        <select name="city" class="text-gray-700 w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 appearance-none transition ">
                            <option class="text-gray-700" value="">All Cities</option>
                            @foreach($cities as $city)
                            <option class="text-gray-700" value="{{ $city }}">{{ $city }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Price Range</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" placeholder="Min Price" value="{{ $minPrice ?? '' }}" class="text-gray-700 placeholder-gray-700 w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        <input type="number" name="max_price" placeholder="Max Price" value="{{ $maxPrice ?? '' }}" class="text-gray-700 placeholder-gray-700 w-full p-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Find Apartment</label>
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 shadow-md">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
            </div>
        </form>

        <!-- Quick access buttons -->
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="#" class="px-6 py-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-sm rounded-full text-white border border-white border-opacity-30 hover:bg-opacity-30 transition">
                <i class="fas fa-home mr-2"></i> Featured Rentals
            </a>
            <a href="#" class="px-6 py-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-sm rounded-full text-white border border-white border-opacity-30 hover:bg-opacity-30 transition">
                <i class="fas fa-calendar-alt mr-2"></i> Last Minute Deals
            </a>
            <a href="#" class="px-6 py-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-sm rounded-full text-white border border-white border-opacity-30 hover:bg-opacity-30 transition">
                <i class="fas fa-star mr-2"></i> Top Rated
            </a>
        </div>
    </div>
</section>

<!-- Top Categories Section - Consistent color scheme -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Rental Options</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4">Top Apartment Categories</h2>
            <div class="h-1 w-24 bg-blue-600 mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-6 max-w-2xl mx-auto">Find the perfect accommodation for your next stay in Jordan from our extensive network of quality properties.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <!-- Category Card 1 -->
            <form action="{{ route('apartments.index') }}" method="GET" class="category-card bg-white p-6 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition duration-300">
                <input type="hidden" name="has_wifi" value="1">
                <button type="submit" class="w-full text-left focus:outline-none d-flex flex-column text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-wifi text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">With WiFi</h3>
                </button>
            </form>

            <!-- Category Card 2 -->
            <form action="{{ route('apartments.index') }}" method="GET" class="category-card bg-white p-6 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition duration-300">
                <input type="hidden" name="has_parking" value="1">
                <button type="submit" class="w-full text-left focus:outline-none d-flex flex-column text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-parking text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">With Parking</h3>
                </button>
            </form>

            <!-- Category Card 3 -->
            <form action="{{ route('apartments.index') }}" method="GET" class="category-card bg-white p-6 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition duration-300">
                <input type="hidden" name="has_kitchen" value="1">
                <button type="submit" class="w-full text-left focus:outline-none d-flex flex-column text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-utensils text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">With Kitchen</h3>
                </button>
            </form>

            <!-- Category Card 4 -->
            <form action="{{ route('apartments.index') }}" method="GET" class="category-card bg-white p-6 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition duration-300">
                <input type="hidden" name="has_air_conditioning" value="1">
                <button type="submit" class="w-full text-left focus:outline-none d-flex flex-column text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-snowflake text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">A/C Included</h3>
                </button>
            </form>

            <!-- Category Card 5 -->
            <form action="{{ route('apartments.index') }}" method="GET" class="category-card bg-white p-6 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition duration-300">
                <input type="hidden" name="city" value="Amman">
                <button type="submit" class="w-full text-left focus:outline-none d-flex flex-column text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-city text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Amman</h3>
                </button>
            </form>

            <!-- Category Card 6 -->
            <form action="{{ route('apartments.index') }}" method="GET" class="category-card bg-white p-6 rounded-xl shadow-md text-center cursor-pointer hover:shadow-lg transition duration-300 justify-content-center ">
                <input type="hidden" name="guests" value="4">
                <button type="submit" class="w-full text-left focus:outline-none d-flex flex-column text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Family Size</h3>
                </button>
            </form>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('apartments.index') }}" class="inline-block px-8 py-3 border-2 border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-600 hover:text-white transition duration-300">
                <span>View All Properties</span>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section - Consistent styling -->
<section class="p-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Why Choose HomeRent</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-6">Rental Made Simple</h2>
                <p class="text-gray-600 mb-8">At HomeRent, we connect guests with the best rental properties across Jordan, making it easy to find apartments and book your stay online.</p>

                <div class="space-y-6">
                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Verified Owners</h3>
                            <p class="text-gray-600">All property owners are verified and their credentials checked.</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Easy Booking</h3>
                            <p class="text-gray-600">Book accommodations online 24/7 without phone calls or waiting.</p>
                        </div>
                    </div>

                    <div class="flex">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Guest Reviews</h3>
                            <p class="text-gray-600">Read authentic reviews and ratings from previous guests.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -top-6 -left-6 w-24 h-24 bg-blue-100 rounded-full z-0"></div>
                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-blue-200 rounded-full z-0"></div>
                <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80" alt="Modern Apartment" class="relative z-10 rounded-xl shadow-xl">
            </div>
        </div>
    </div>
</section>

<!-- FAQs Section - Updated to match Tailwind styling and add click functionality -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-blue-600 font-semibold text-sm uppercase tracking-wider">Have Questions?</span>
            <h2 class="text-3xl md:text-4xl font-bold mt-2 mb-4">Frequently Asked Questions</h2>
            <div class="h-1 w-24 bg-blue-600 mx-auto rounded-full"></div>
            <p class="text-gray-600 mt-6 max-w-2xl mx-auto">Find answers to the most common questions about using our platform.</p>
        </div>

        <div class="max-w-3xl mx-auto">
            <!-- FAQ Item 1 -->
            <div class="mb-4">
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 focus:outline-none rounded-lg" onclick="toggleFaq('faq1')">
                        <div class="flex items-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-full mr-3">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <span class="font-semibold text-gray-800">How do I book an apartment?</span>
                        </div>
                        <i id="faq1-icon" class="fas fa-chevron-down text-blue-600 transition-transform duration-300"></i>
                    </button>
                    <div id="faq1-answer" class="hidden p-5 pt-0 pl-20">
                        <p class="text-gray-600">You can easily book an apartment by using our search feature to find a property, selecting available dates that work for you, and confirming your booking. The entire process takes just a few minutes.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 2 -->
            <div class="mb-4">
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 focus:outline-none rounded-lg" onclick="toggleFaq('faq2')">
                        <div class="flex items-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-full mr-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <span class="font-semibold text-gray-800">Is there a security deposit required?</span>
                        </div>
                        <i id="faq2-icon" class="fas fa-chevron-down text-blue-600 transition-transform duration-300"></i>
                    </button>
                    <div id="faq2-answer" class="hidden p-5 pt-0 pl-20">
                        <p class="text-gray-600">Yes, most properties require a security deposit which is fully refundable if the apartment is left in good condition. The specific amount varies by property and is clearly stated on each listing.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ Item 3 -->
            <div class="mb-4">
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full flex justify-between items-center p-5 text-left bg-white hover:bg-gray-50 focus:outline-none rounded-lg" onclick="toggleFaq('faq3')">
                        <div class="flex items-center">
                            <div class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 text-blue-600 rounded-full mr-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span class="font-semibold text-gray-800">Can I cancel or reschedule my booking?</span>
                        </div>
                        <i id="faq3-icon" class="fas fa-chevron-down text-blue-600 transition-transform duration-300"></i>
                    </button>
                    <div id="faq3-answer" class="hidden p-5 pt-0 pl-20">
                        <p class="text-gray-600">Yes, you can modify or cancel your booking through your account dashboard. Please make sure to do this at least 48 hours before your scheduled check-in for a full refund according to our cancellation policy.</p>
                    </div>
                </div>
            </div>

            <!-- Add more FAQ items as needed -->
        </div>
    </div>

    <!-- JavaScript for FAQ toggle functionality -->
    <script>
        function toggleFaq(id) {
            const answer = document.getElementById(id + '-answer');
            const icon = document.getElementById(id + '-icon');

            if (answer.classList.contains('hidden')) {
                answer.classList.remove('hidden');
                icon.classList.add('transform', 'rotate-180');
            } else {
                answer.classList.add('hidden');
                icon.classList.remove('transform', 'rotate-180');
            }
        }
    </script>
</section>

@endsection