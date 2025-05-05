@extends('admindashboard.layout')

@section('title' , 'Edit Patient')

@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Patient Information</h3>
                </div>
                
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" value="{{ $patient->user->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Personal Information</h4>
                                <hr>
                                
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $patient->user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $patient->user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Add Role Selection -->
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                        <option value="patient" {{ old('role', $patient->user->role) == 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="doctor" {{ old('role', $patient->user->role) == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="admin" {{ old('role', $patient->user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="number" name="age" id="age" class="form-control @error('age') is-invalid @enderror" 
                                           value="{{ old('age', $patient->age) }}" required>
                                    @error('age')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Medical History</h4>
                                <hr>
                                
                                <div class="form-group">
                                    <label for="chronic_diseases">Chronic Diseases</label>
                                    <textarea name="chronic_diseases" id="chronic_diseases" rows="3" 
                                              class="form-control @error('chronic_diseases') is-invalid @enderror">{{ old('chronic_diseases', $patient->medicalHistory->chronic_diseases ?? '') }}</textarea>
                                    @error('chronic_diseases')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="medications">Medications</label>
                                    <textarea name="medications" id="medications" rows="3" 
                                              class="form-control @error('medications') is-invalid @enderror">{{ old('medications', $patient->medicalHistory->medications ?? '') }}</textarea>
                                    @error('medications')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="allergies">Allergies</label>
                                    <textarea name="allergies" id="allergies" rows="3" 
                                              class="form-control @error('allergies') is-invalid @enderror">{{ old('allergies', $patient->medicalHistory->allergies ?? '') }}</textarea>
                                    @error('allergies')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" rows="3" 
                                              class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $patient->medicalHistory->notes ?? '') }}</textarea>
                                    @error('notes')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Appointments Section -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Appointments</h4>
                                <hr>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Doctor</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($appointments) && count($appointments) > 0)
                                                @foreach($appointments as $appointment)
                                                <tr>
                                                    <td>{{ $appointment->doctor->user->name ?? 'N/A' }}</td>
                                                    <td>{{ $appointment->date }}</td>
                                                    <td>{{ $appointment->time }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($appointment->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-info btn-sm">Edit</a>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                onclick="deleteAppointment({{ $appointment->id }})">Delete</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No appointments found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#scheduleAppointmentModal">
                                    Schedule New Appointment
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update Patient Information</button>
                            <a href="{{ route('pat') }}" class="btn btn-secondary">Cancel</a>
                            <button type="button" class="btn btn-danger float-right" onclick="confirmDelete()">Delete User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Appointment Modal -->
<div class="modal fade" id="scheduleAppointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="appointmentModalLabel">Schedule New Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.appointments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    
                    <div class="form-group">
                        <label for="doctor_id">Select Doctor</label>
                        <select name="doctor_id" id="doctor_id" class="form-control" required>
                            <option value="">-- Select Doctor --</option>
                            @if(isset($doctors) && count($doctors) > 0)
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_date">Date</label>
                        <input type="date" name="date" id="appointment_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_time">Time</label>
                        <input type="time" name="time" id="appointment_time" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_status">Status</label>
                        <select name="status" id="appointment_status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="appointment_notes">Notes</label>
                        <textarea name="notes" id="appointment_notes" rows="3" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (hidden) -->
<form id="delete-user-form" action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

<script>
    // Delete appointment function
    function deleteAppointment(appointmentId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This appointment will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form dynamically
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ url("admin/appointments") }}/' + appointmentId;
                form.innerHTML = '@csrf @method("DELETE")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Delete user function
    function confirmDelete() {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete this user, patient record, and all related data!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-user-form').submit();
            }
        });
    }
</script>
@endsection