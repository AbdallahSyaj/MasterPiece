<nav class="bg-white shadow-md py-4">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">

            <a href="/" class="flex items-center">
                <span class="text-2xl font-bold text-primary">med<span class="text-gray-800">Xpert</span></span>
            </a>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" class="mobile-menu-button text-gray-500 hover:text-primary focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{route('home')}}" class="text-gray-700 hover:text-blue-600 font-medium">Home</a>
                <a href="{{route('doc')}}" class="text-gray-700 hover:text-blue-600 font-medium">Find Doctors</a>
                <a href="{{route('about')}}" class="text-gray-700 hover:text-blue-600 font-medium">About Us</a>
                <a href="{{route('contact')}}" class="text-gray-700 hover:text-blue-600 font-medium">Contact</a>
            </div>

            <!-- Login/Register or User Menu based on session -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center px-4 py-2 text-primary font-medium hover:text-primary-dark transition-colors duration-200">
                            <span>Welcome, {{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                            @if(Auth::user()->role == 'doctor')
                            <a href="{{ route('profile', ['id' => Auth::id()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        @else
                            <a href="{{ route('patientprofile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        @endif
                            <form action="{{ route('logout') }}" method="post" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-primary font-medium hover:text-primary-dark transition-colors duration-200">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors duration-200 font-medium shadow-md">Register</a>
                @endauth
            </div>
        </div>

        <!-- Mobile Menu (hidden by default) -->
        <div class="mobile-menu hidden flex-col mt-4 md:hidden">
            <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2">Home</a>
            <a href="{{ route('doc') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2">Find Doctors</a>
            <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2">About Us</a>
            <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 font-medium py-2">Contact</a>
            
            <div class="flex flex-col space-y-2 mt-3 pt-3 border-t border-gray-200">
                @auth
                    <span class="py-2 font-medium">Welcome, {{ Auth::user()->name }}</span>
                    @if(Auth::user()->role == 'doctor')
    <a href="{{ route('profile', ['id' => Auth::id()]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
@else
    <a href="{{ route('patientprofile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
@endif
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-primary font-medium">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="py-2 text-primary font-medium">Login</a>
                    <a href="{{ route('register') }}" class="py-2 bg-primary text-white rounded-lg text-center">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
