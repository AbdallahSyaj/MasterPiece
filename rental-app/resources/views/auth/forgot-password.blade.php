{{-- resources/views/auth/forgot-password.blade.php --}}

<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<div class="container">
    <div class="register-card">
        <h1>Forgot Password</h1>
        
        <div class="intro-text">
            Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
        </div>
        
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn-primary">Email Password Reset Link</button>
            
            <div class="login-link">
                <a href="{{ route('login') }}">Back to login</a>
            </div>
        </form>
    </div>
</div>

<style>
/* Additional styles specific to forgot password */
.intro-text {
    margin-bottom: 20px;
    color: #7f8c8d;
    text-align: center;
    line-height: 1.5;
}

.status-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
}

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