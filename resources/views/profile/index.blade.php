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

        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        
        .profile-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .profile-image-section {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent-color);
        }

        .profile-image-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--light-text);
            border: 3px solid var(--accent-color);
        }

        .profile-info {
            margin-left: 1.5rem;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .profile-role {
            color: var(--light-text);
            font-size: 0.9rem;
            text-transform: capitalize;
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: var(--light-bg);
            border-radius: 20px;
            margin-bottom: 0.5rem;
        }

        .form-section {
            margin-bottom: 1rem;
        }

        .form-section-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(82, 183, 136, 0.1);
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(229, 56, 59, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
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

        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .btn-warning {
            background-color: white;
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .btn-warning:hover {
            background-color: var(--warning);
            color: white;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .card-header .badge {
            background: var(--light-bg);
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            color: var(--primary-color);
        }

        .items-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .item-card {
            padding: 1rem;
            border-radius: var(--border-radius);
            background-color: var(--light-bg);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-card:last-child {
            margin-bottom: 0;
        }

        .item-info {
            flex-grow: 1;
        }

        .item-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .item-details {
            color: var(--light-text);
            font-size: 0.85rem;
        }

        .item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }

        .data-section {
            margin-top: 2.5rem;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .profile-header .actions {
                margin-top: 1rem;
                width: 100%;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-image-section {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-info {
                margin-left: 0;
                margin-top: 1rem;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1 class="page-title">Edit Profile</h1>
        <div class="actions">
            @if($user->role === 'owner')
                <a href="{{ route('apartments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Property
                </a>
            @endif
            <a href="{{ route('password.change') }}" class="btn btn-warning">
                <i class="fas fa-lock"></i> Change Password
            </a>
        </div>
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

    <div class="profile-card">
        <div class="profile-image-section">
            @if(isset($owner) && $owner->profile_image)
                <img src="{{ $owner->profile_image }}" alt="{{ $user->name }}" class="profile-image">
            @else
                <div class="profile-image-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <div class="profile-info">
                <h2 class="profile-name">{{ $user->name }}</h2>
                <div class="profile-role">{{ $user->role }}</div>
                <div>{{ $user->email }}</div>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-user-circle"></i> Basic Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
            </div>

            <!-- Tenant-Specific Fields -->
            @if($user->role === 'tenant' && isset($tenant))
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-address-card"></i> Personal Details
                    </h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $tenant->phone) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" name="age" value="{{ old('age', $tenant->age) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" name="gender">
                                <option value="male" {{ $tenant->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $tenant->gender === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $tenant->gender === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" name="address">{{ old('address', $tenant->address) }}</textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Owner-Specific Fields -->
            @if($user->role === 'owner' && isset($owner))
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-address-card"></i> Contact Information
                    </h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $owner->phone) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" name="address">{{ old('address', $owner->address) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-user-circle"></i> Profile Details
                    </h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" name="bio">{{ old('bio', $owner->bio) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="profile_image" class="form-label">Profile Image URL</label>
                            <input type="text" class="form-control" name="profile_image" value="{{ old('profile_image', $owner->profile_image) }}">
                        </div>
                    </div>
                </div>
            @endif

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Tenant Bookings -->
    @if($user->role === 'tenant' && isset($bookings) && count($bookings) > 0)
        <div class="profile-card data-section">
            <div class="card-header">
                <h3><i class="fas fa-calendar-check"></i> Your Bookings</h3>
                <span class="badge">{{ count($bookings) }} total</span>
            </div>
            
            <ul class="items-list">
                @foreach($bookings as $booking)
                    <li class="item-card">
                        <div class="item-info">
                            <div class="item-title">{{ $booking->apartment->title ?? 'N/A' }}</div>
                            <div class="item-details">
                                <i class="fas fa-calendar-alt"></i> Check-in: {{ $booking->check_in_date }} | Check-out: {{ $booking->check_out_date }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <a href="#" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Owner Apartments -->
    @if($user->role === 'owner' && isset($apartments) && count($apartments) > 0)
        <div class="profile-card data-section">
            <div class="card-header">
                <h3><i class="fas fa-home"></i> Your Properties</h3>
                <span class="badge">{{ count($apartments) }} total</span>
            </div>
            
            <ul class="items-list">
                @foreach($apartments as $apartment)
                    <li class="item-card">
                        <div class="item-info">
                            <div class="item-title">{{ $apartment->title }}</div>
                            <div class="item-details">
                                <i class="fas fa-map-marker-alt"></i> {{ $apartment->city }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <a href="{{ route('apartments.edit', $apartment->id) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('apartments.show', $apartment->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection




{{-- @extends('layouts.app')

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

        .profile-container {
            max-width: 1000px;
            margin: 2rem auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        
        .profile-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
        }

        .profile-image-section {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--accent-color);
        }

        .profile-image-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--light-text);
            border: 3px solid var(--accent-color);
        }

        .profile-info {
            margin-left: 1.5rem;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .profile-role {
            color: var(--light-text);
            font-size: 0.9rem;
            text-transform: capitalize;
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background-color: var(--light-bg);
            border-radius: 20px;
            margin-bottom: 0.5rem;
        }

        .form-section {
            margin-bottom: 1rem;
        }

        .form-section-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-right: 0.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .alert {
            padding: 1rem;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: rgba(82, 183, 136, 0.1);
            border-left: 4px solid var(--success);
            color: var(--success);
        }

        .alert-danger {
            background-color: rgba(229, 56, 59, 0.1);
            border-left: 4px solid var(--danger);
            color: var(--danger);
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

        .btn-secondary {
            background-color: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }

        .btn-secondary:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }

        .btn-warning {
            background-color: white;
            color: var(--warning);
            border: 1px solid var(--warning);
        }

        .btn-warning:hover {
            background-color: var(--warning);
            color: white;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .card-header .badge {
            background: var(--light-bg);
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            color: var(--primary-color);
        }

        .items-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .item-card {
            padding: 1rem;
            border-radius: var(--border-radius);
            background-color: var(--light-bg);
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-card:last-child {
            margin-bottom: 0;
        }

        .item-info {
            flex-grow: 1;
        }

        .item-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .item-details {
            color: var(--light-text);
            font-size: 0.85rem;
        }

        .item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }

        .data-section {
            margin-top: 2.5rem;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .profile-header .actions {
                margin-top: 1rem;
                width: 100%;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-image-section {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-info {
                margin-left: 0;
                margin-top: 1rem;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1 class="page-title">Edit Profile</h1>
        <div class="actions">
            <a href="{{ route('password.change') }}" class="btn btn-warning">
                <i class="fas fa-lock"></i> Change Password
            </a>
        </div>
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

    <div class="profile-card">
        <div class="profile-image-section">
            @if(isset($owner) && $owner->profile_image)
                <img src="{{ $owner->profile_image }}" alt="{{ $user->name }}" class="profile-image">
            @else
                <div class="profile-image-placeholder">
                    <i class="fas fa-user"></i>
                </div>
            @endif
            <div class="profile-info">
                <h2 class="profile-name">{{ $user->name }}</h2>
                <div class="profile-role">{{ $user->role }}</div>
                <div>{{ $user->email }}</div>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-section">
                <h3 class="form-section-title">
                    <i class="fas fa-user-circle"></i> Basic Information
                </h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                </div>
            </div>

            <!-- Tenant-Specific Fields -->
            @if($user->role === 'tenant' && isset($tenant))
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-address-card"></i> Personal Details
                    </h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $tenant->phone) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" name="age" value="{{ old('age', $tenant->age) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" name="gender">
                                <option value="male" {{ $tenant->gender === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $tenant->gender === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ $tenant->gender === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" name="address">{{ old('address', $tenant->address) }}</textarea>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Owner-Specific Fields -->
            @if($user->role === 'owner' && isset($owner))
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-address-card"></i> Contact Information
                    </h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone', $owner->phone) }}">
                        </div>
                        
                        <div class="form-group">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" name="address">{{ old('address', $owner->address) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3 class="form-section-title">
                        <i class="fas fa-user-circle"></i> Profile Details
                    </h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" name="bio">{{ old('bio', $owner->bio) }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="profile_image" class="form-label">Profile Image URL</label>
                            <input type="text" class="form-control" name="profile_image" value="{{ old('profile_image', $owner->profile_image) }}">
                        </div>
                    </div>
                </div>
            @endif

            <div class="actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Tenant Bookings -->
    @if($user->role === 'tenant' && isset($bookings) && count($bookings) > 0)
        <div class="profile-card data-section">
            <div class="card-header">
                <h3><i class="fas fa-calendar-check"></i> Your Bookings</h3>
                <span class="badge">{{ count($bookings) }} total</span>
            </div>
            
            <ul class="items-list">
                @foreach($bookings as $booking)
                    <li class="item-card">
                        <div class="item-info">
                            <div class="item-title">{{ $booking->apartment->title ?? 'N/A' }}</div>
                            <div class="item-details">
                                <i class="fas fa-calendar-alt"></i> Check-in: {{ $booking->check_in_date }} | Check-out: {{ $booking->check_out_date }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <a href="#" class="btn btn-secondary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Owner Apartments -->
    @if($user->role === 'owner' && isset($apartments) && count($apartments) > 0)
        <div class="profile-card data-section">
            <div class="card-header">
                <h3><i class="fas fa-home"></i> Your Properties</h3>
                <span class="badge">{{ count($apartments) }} total</span>
            </div>
            
            <ul class="items-list">
                @foreach($apartments as $apartment)
                    <li class="item-card">
                        <div class="item-info">
                            <div class="item-title">{{ $apartment->title }}</div>
                            <div class="item-details">
                                <i class="fas fa-map-marker-alt"></i> {{ $apartment->city }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <a href="#" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="#" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection --}}