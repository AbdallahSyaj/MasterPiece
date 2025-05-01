@extends('layouts.app')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush
@section('title', 'Contact Us')


@section('content')
{{-- {{dd(session()->all())}} --}}
{{dd (session('user_id')) }}

    <div class="container my-5">
        <h1 class="text-center animate__animated animate__fadeInDown">Contact Us</h1>
        <p class="text-center text-muted animate__animated animate__fadeInUp">Feel free to reach out to us anytime!</p>

        <!-- قسم نموذج الاتصال -->
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="contact-form p-4 rounded shadow">
                    <form action="/contact-submit" method="POST">
                        @csrf <!-- أضف رمز الحماية CSRF -->

                        <!-- حقل الاسم -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>

                        <!-- حقل البريد الإلكتروني -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                        </div>

                        <!-- حقل الرسالة -->
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" placeholder="Write your message here" required></textarea>
                        </div>

                        <!-- زر الإرسال -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-4 py-2 shadow animate__animated animate__pulse animate__infinite">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
        @endif
    </div>
@endsection