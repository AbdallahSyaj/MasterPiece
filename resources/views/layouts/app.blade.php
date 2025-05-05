<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'medXpert - Home')</title>

    <!-- Include Tailwind CSS via CDN for the design -->
    @stack('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Primary Color Palette */
            --primary-50: #EBF5FF;
            --primary-100: #E1EFFE;
            --primary-200: #C3DDFD;
            --primary-300: #A4CAFE;
            --primary-400: #76A9FA;
            --primary-500: #3F83F8;
            --primary-600: #1C64F2;
            --primary-700: #1A56DB;
            --primary-800: #1E429F;
            --primary-900: #233876;

            /* Gray Scale */
            --gray-50: #F9FAFB;
            --gray-100: #F4F5F7;
            --gray-200: #E5E7EB;
            --gray-300: #D2D6DC;
            --gray-400: #9FA6B2;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #252F3F;
            --gray-900: #161E2E;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background-image: linear-gradient(rgba(28, 100, 242, 0.85), rgba(30, 66, 159, 0.9)),
            url('{{ asset("images/medical-bg.jpg") }}');
            background-size: cover;
            background-position: center;
            height: 500px;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-card {
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Standard blue color classes for consistency */
        .text-primary {
            color: var(--primary-600);
        }

        .bg-primary {
            background-color: var(--primary-600);
        }

        .border-primary {
            border-color: var(--primary-600);
        }

        .hover\:bg-primary-dark:hover {
            background-color: var(--primary-700);
        }

        .hover\:text-primary:hover {
            color: var(--primary-600);
        }

        .btn-primary {
            background-color: #0275d8;
            border-color: #0275d8;
        }

        .card-header {
            background-color: #0275d8;
            color: white;
        }

        .profile-sidebar {
            background-color: #0275d8;
            color: white;
            height: 100%;
            min-height: 100vh;
        }

        .profile-sidebar a {
            color: white;
            text-decoration: none;
            padding: 15px;
            display: block;
        }

        .profile-sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            padding: 20px;
        }

        .required::after {
            content: "*";
            color: red;
        }
    </style>

</head>

<body class="bg-gray-50">
    <!-- Include Navigation Bar -->




    @include('components.navbar')


    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Include Footer -->
    @include('components.footer')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        // Simple mobile menu toggle
        $(document).ready(function() {
            $('.mobile-menu-button').click(function() {
                $('.mobile-menu').toggleClass('hidden flex');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>