@extends('layouts.app')

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #333;
            --light-text: #888;
            --danger: #e5383b;
            --warning: #ffb703;
            --success: #52b788;
            --border-radius: 10px;
            --box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-text);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .change-password-container {
            max-width: 800px;
            margin: 4rem auto;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-weight: 700;
            color: var(--dark-text);
            font-size: 1.75rem;
            position: relative;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 4px;
            background-color: var(--primary-color);
            border-radius: 2px;
        }

        .security-card {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
        }

        .security-card-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 2rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .security-card-header i {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }

        .security-card-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-text);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e1e5eb;
            border-radius: var(--border-radius);
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        .is-invalid {
            border-color: var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.85rem;
            margin-top: 0.35rem;
        }

        .password-strength {
            margin-top: 0.5rem;
            height: 5px;
            background-color: #e1e5eb;
            border-radius: 3px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            background-color: var(--danger);
            transition: all 0.3s ease;
        }

        .password-strength-text {
            font-size: 0.75rem;
            color: var(--light-text);
            margin-top: 0.35rem;
        }

        .weak {
            width: 33.33%;
            background-color: var(--danger);
        }

        .medium {
            width: 66.66%;
            background-color: var(--warning);
        }

        .strong {
            width: 100%;
            background-color: var(--success);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
        }

        .btn-light {
            background-color: var(--light-bg);
            color: var(--dark-text);
        }

        .btn-light:hover {
            background-color: #e2e6ea;
        }

        .form-actions {
            display: flex;
            gap: a1rem;
            margin-top: 2rem;
        }

        .password-info {
            font-size: 0.85rem;
            color: var(--light-text);
            margin-top: 2rem;
            background-color: var(--light-bg);
            padding: 1rem;
            border-radius: var(--border-radius);
            position: relative;
        }

        .password-info-header {
            font-weight: 600;
            color: var(--dark-text);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .password-info-header i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .password-rules {
            list-style: none;
            padding: 0;
            margin: 0.5rem 0 0 0;
        }

        .password-rules li {
            margin-bottom: 0.35rem;
            padding-left: 1.5rem;
            position: relative;
        }

        .password-rules li:before {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--success);
        }

        .show-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--light-text);
            cursor: pointer;
        }

        .password-wrapper {
            position: relative;
        }

        @media (max-width: 768px) {
            .change-password-container {
                padding: 0 1rem;
            }
            
            .security-card-header,
            .security-card-body {
                padding: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="change-password-container">
    <div class="page-header">
        <h1 class="page-title">Change Password</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="security-card">
        <div class="security-card-header">
            <i class="fas fa-lock"></i>
            <span>Update Your Password</span>
        </div>
        <div class="security-card-body">
            <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
                @csrf

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <div class="password-wrapper">
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
                        <button type="button" class="show-password" data-target="current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <div class="password-wrapper">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                        <button type="button" class="show-password" data-target="password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <div class="password-strength-text" id="passwordStrengthText">Password strength</div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">Confirm New Password</label>
                    <div class="password-wrapper">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        <button type="button" class="show-password" data-target="password-confirm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="passwordMatch" class="password-strength-text"></div>
                </div>

                <div class="password-info">
                    <div class="password-info-header">
                        <i class="fas fa-shield-alt"></i> Password Requirements
                    </div>
                    <p>For your security, your password must:</p>
                    <ul class="password-rules">
                        <li>Be at least 8 characters long</li>
                        <li>Include at least one uppercase letter</li>
                        <li>Include at least one number</li>
                        <li>Include at least one special character</li>
                    </ul>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                    <a href="{{ route('profile') }}" class="btn btn-light ms-2">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.querySelectorAll('.show-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const passwordInput = document.getElementById(targetId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    });

    // Password strength meter
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    const confirmInput = document.getElementById('password-confirm');
    const passwordMatch = document.getElementById('passwordMatch');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Check password length
        if (password.length >= 8) {
            strength += 1;
        }
        
        // Check for uppercase letters
        if (/[A-Z]/.test(password)) {
            strength += 1;
        }
        
        // Check for numbers
        if (/[0-9]/.test(password)) {
            strength += 1;
        }
        
        // Check for special characters
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 1;
        }
        
        // Update strength bar
        strengthBar.className = 'password-strength-bar';
        if (strength === 0) {
            strengthBar.style.width = '0';
            strengthText.textContent = 'Password strength';
        } else if (strength <= 2) {
            strengthBar.classList.add('weak');
            strengthText.textContent = 'Weak password';
        } else if (strength === 3) {
            strengthBar.classList.add('medium');
            strengthText.textContent = 'Medium strength password';
        } else {
            strengthBar.classList.add('strong');
            strengthText.textContent = 'Strong password';
        }
        
        // Check if passwords match
        if (confirmInput.value !== '') {
            checkPasswordMatch();
        }
    });

    // Check if passwords match
    confirmInput.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
        if (passwordInput.value === confirmInput.value) {
            passwordMatch.textContent = 'Passwords match ✓';
            passwordMatch.style.color = 'var(--success)';
        } else {
            passwordMatch.textContent = 'Passwords do not match ✗';
            passwordMatch.style.color = 'var(--danger)';
        }
    }

    // Form validation
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match.');
            return false;
        }
        
        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            return false;
        }
        
        return true;
    });
</script>
@endpush
@endsection