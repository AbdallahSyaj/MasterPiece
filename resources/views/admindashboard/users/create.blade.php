@extends('admindashboard.layout')

@section('title', 'Create New User')

@section('contant')
<!-- Main Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Create New User</h1>
    <p class="mb-4">Add a new user to the system with appropriate role (admin, doctor, or patient).</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <!-- Basic User Information -->
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">User Role <span class="text-danger">*</span></label>
                    <select class="form-control" id="role" name="role" required onchange="toggleRoleFields(this.value)">
                        <option value="">Select a role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="doctor" {{ old('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                        <option value="patient" {{ old('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    </select>
                </div>

                <!-- Patient-specific fields -->
                <div id="patient-fields" style="display: none;">
                    <h5 class="mt-4 mb-3">Patient Information</h5>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="age">Age</label>
                            <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}">
                        </div>
                    </div>
                </div>

                <!-- Doctor-specific fields -->
                <div id="doctor-fields" style="display: none;">
                    <h5 class="mt-4 mb-3">Doctor Information</h5>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="specialty">Specialty</label>
                            <input type="text" class="form-control" id="specialty" name="specialty" value="{{ old('specialty') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="experience_years">Years of Experience</label>
                            <input type="number" class="form-control" id="experience_years" name="experience_years" value="{{ old('experience_years') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="clinic_address">Clinic Address</label>
                            <input type="text" class="form-control" id="clinic_address" name="clinic_address" value="{{ old('clinic_address') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="city">City</label>
                            <select class="form-control" id="city" name="city">
                                <option value="Amman" {{ old('city') == 'Amman' ? 'selected' : '' }}>Amman</option>
                                <option value="Zarqa" {{ old('city') == 'Zarqa' ? 'selected' : '' }}>Zarqa</option>
                                <option value="Irbid" {{ old('city') == 'Irbid' ? 'selected' : '' }}>Irbid</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="price">Consultation Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <select class="form-control" id="rating" name="rating">
                            <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                            <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                            <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                            <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                            <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Function to toggle fields based on selected role
    function toggleRoleFields(role) {
        const patientFields = document.getElementById('patient-fields');
        const doctorFields = document.getElementById('doctor-fields');
        
        patientFields.style.display = 'none';
        doctorFields.style.display = 'none';
        
        if (role === 'patient') {
            patientFields.style.display = 'block';
        } else if (role === 'doctor') {
            doctorFields.style.display = 'block';
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value) {
            toggleRoleFields(roleSelect.value);
        }
    });
</script>
@endsection