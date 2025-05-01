@extends('layouts.app')

@section('title', 'About Us')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')

    <div class="container my-5">
        <h1 class="text-center animate__animated animate__fadeInDown">About Us</h1>
        <p class="text-center text-muted">Welcome to Midxpert, your trusted healthcare solution.</p>

        <!-- الفقرة الجديدة -->
        <div class="about-section mt-4">
            <p>
                MedXpert is the leading e-healthcare platform for doctor booking and clinic management software in the Middle East. 
                We are leading the transformation of doctor, clinic, and hospital bookings to digital and automated systems, 
                making high-quality healthcare more accessible in the Arab region.
            </p>
        </div>

        <!-- قسم الفريق -->
        <div class="container my-5">
            <h2 class="text-center mb-4 animate__animated animate__fadeInUp">Our Team</h2>
            <div class="row justify-content-center g-4">
                <!-- Heba Alajouri -->
                <div class="col-md-4 text-center mb-4">
                    <div class="team-card">
                        <img src="../medImage/heba.jpg" alt="Heba Alajouri" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Heba Alajouri</h5>
                        <p>Scrum Master</p>
                    </div>
                </div>

                <!-- Joud Krasneh -->
                <div class="col-md-4 text-center mb-4">
                    <div class="team-card">
                        <img src="../medImage/jod.jpg" alt="Joud Krasneh" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Joud Krasneh</h5>
                        <p>Product Owner</p>
                    </div>
                </div>

                <!-- Hasan Darweesh -->
                <div class="col-md-4 text-center mb-4">
                    <div class="team-card">
                        <img src="../medImage/hasan.jpg" alt="Hasan Darweesh" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Hasan Darweesh</h5>
                        <p>Full Stack Developer</p>
                    </div>
                </div>

                <!-- Abd Allah Syaj -->
                <div class="col-md-4 text-center mb-4">
                    <div class="team-card">
                        <img src="../medImage/syaj.jpg" alt="Abd Allah Syaj" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Abd Allah Syaj</h5>
                        <p>Full Stack Developer</p>
                    </div>
                </div>

                <!-- Zaid Abu Shanab -->
                <div class="col-md-4 text-center mb-4">
                    <div class="team-card">
                        <img src="../medImage/shanab.jpg" alt="Zaid Abu Shanab" class="rounded-circle mb-3" width="150" height="150">
                        <h5>Zaid Abu Shanab</h5>
                        <p>Full Stack Developer</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection