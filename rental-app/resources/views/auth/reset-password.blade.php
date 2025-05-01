{{-- resources/views/auth/reset-password.blade.php --}}

<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<div class="container">
    <div class="register-card">
        <h1>Reset Password</h1>
        
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn-primary">Reset Password</button>
            
            <div class="login-link">
                <a href="{{ route('login') }}">Back to login</a>
            </div>
        </form>
    </div>
</div>

<style>
/* Additional styles specific to password reset */
.login-link {
    text-align: center;
    margin-top: 20px;
}

.login-link a {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.login-link a:hover {
    text-decoration: underline;
}
</style>