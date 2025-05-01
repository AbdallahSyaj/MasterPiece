{{-- resources/views/auth/login.blade.php --}}

<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

<div class="container">
    <div class="register-card">
        <h1>Login to Your Account</h1>
        
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
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
            
            <div class="form-group remember-me">
                <label for="remember_me" class="checkbox-container">
                    <input type="checkbox" id="remember_me" name="remember">
                    <span class="checkmark"></span>
                    <span class="remember-text">Remember me</span>
                </label>
            </div>
            
            <button type="submit" class="btn-primary">Login</button>
            
            <div class="login-links">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
                
                <a href="{{ route('register') }}">Don't have an account? Register here</a>
            </div>
        </form>
    </div>
</div>

<style>
/* Additional styles specific to login */
.status-message {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
}

.remember-me {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.remember-text {
    margin-left: 8px;
    color: #7f8c8d;
}

.login-links {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
    gap: 10px;
}

.login-links a {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
}

.login-links a:hover {
    text-decoration: underline;
}

/* Styled checkbox */
.checkbox-container {
    display: flex;
    align-items: center;
    position: relative;
    cursor: pointer;
    user-select: none;
}

.checkbox-container input {
    width: auto;
    margin-right: 0;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: relative;
    top: 0;
    left: 0;
    height: 20px;
    width: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.checkbox-container:hover input ~ .checkmark {
    background-color: #f5f5f5;
}

.checkbox-container input:checked ~ .checkmark {
    background-color: #3498db;
    border-color: #3498db;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
    display: block;
}

.checkbox-container .checkmark:after {
    left: 7px;
    top: 3px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
</style>