{{-- resources/views/auth/register.blade.php --}}
{{-- 
@extends('layouts.app')

@section('title', 'Register - Property Rental')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@endsection --}}

{{-- @section('content') --}}

<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<div class="container">
    <div class="register-card">
        <h1>Create Your Account</h1>
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Username</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                @error('phone_number')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="national_id">National ID</label>
                <input type="text" id="national_id" name="national_id" value="{{ old('national_id') }}" required>
                @error('national_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label>Account Type</label>
                <div class="role-selector">
                    <div class="role-option {{ old('role') == 'owner' ? 'selected' : '' }}" data-role="owner">
                        <i class="fas fa-home"></i>
                        <div>Property Owner</div>
                    </div>
                    <div class="role-option {{ old('role', 'tenant') == 'tenant' ? 'selected' : '' }}" data-role="tenant">
                        <i class="fas fa-key"></i>
                        <div>Tenant</div>
                    </div>
                </div>
                <input type="hidden" id="role" name="role" value="{{ old('role', 'tenant') }}">
                @error('role')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <button type="submit" class="btn-primary">Register</button>
            
            <div class="login-link">
                Already have an account? <a href="{{ route('login') }}">Login here</a>
            </div>
        </form>
    </div>
</div>
{{-- @endsection

@section('scripts') --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize selected role
        const roleInput = document.getElementById('role');
        
        // Add click event listeners to role options
        document.querySelectorAll('.role-option').forEach(option => {
            option.addEventListener('click', function() {
                // Remove selected class from all options
                document.querySelectorAll('.role-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                // Update hidden input value
                roleInput.value = this.dataset.role;
            });
        });
    });
</script>
{{-- @endsection --}}