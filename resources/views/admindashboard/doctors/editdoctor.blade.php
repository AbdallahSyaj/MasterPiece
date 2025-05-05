@extends('admindashboard.layout')

@section('title', 'Edit Doctor')

@section('contant')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Doctor Information</h3>
                </div>
                
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="user_id" value="{{ $doctor->user->id }}">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Personal Information</h4>
                                <hr>
                                
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $doctor->user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $doctor->user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Add Role Selection -->
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                        <option value="patient" {{ old('role', $doctor->user->role) == 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="doctor" {{ old('role', $doctor->user->role) == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                        <option value="admin" {{ old('role', $doctor->user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    @error('role')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Add Phone Number -->
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           value="{{ old('phone', $doctor->doctorDetails->phone ?? '') }}" maxlength="10" required>
                                    @error('phone')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Experience Years -->
                                <div class="form-group">
                                    <label for="experience_years">Experience (Years)</label>
                                    <input type="number" name="experience_years" id="experience_years" class="form-control @error('experience_years') is-invalid @enderror" 
                                           value="{{ old('experience_years', $doctor->doctorDetails->experience_years ?? '') }}" required min="0">
                                    @error('experience_years')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Professional Details</h4>
                                <hr>
                                
                                <div class="form-group">
                                    <label for="specialty">Specialty</label>
                                    <input type="text" name="specialty" id="specialty" class="form-control @error('specialty') is-invalid @enderror" 
                                           value="{{ old('specialty', $doctor->doctorDetails->specialty ?? '') }} " placeholder="{{ old('specialty', $doctor->doctorDetails->specialty ?? '') }}" required>
                                    @error('specialty')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="clinic_address">Clinic Address</label>
                                    <textarea name="clinic_address" id="clinic_address" rows="3" 
                                              class="form-control @error('clinic_address') is-invalid @enderror" required>{{ old('clinic_address', $doctor->doctorDetails->clinic_address ?? '') }}</textarea>
                                    @error('clinic_address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" 
                                           value="{{ old('city', $doctor->doctorDetails->city ?? '') }}" required>
                                    @error('city')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="price">Consultation Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" name="price" id="price" step="0.01" min="0" 
                                               class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ old('price', $doctor->doctorDetails->price ?? '') }}" required>
                                    </div>
                                    @error('price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <select name="rating" id="rating" class="form-control @error('rating') is-invalid @enderror" required>
                                        <option value="1" {{ old('rating', $doctor->doctorDetails->rating ?? '') == '1' ? 'selected' : '' }}>1 Star</option>
                                        <option value="2" {{ old('rating', $doctor->doctorDetails->rating ?? '') == '2' ? 'selected' : '' }}>2 Stars</option>
                                        <option value="3" {{ old('rating', $doctor->doctorDetails->rating ?? '') == '3' ? 'selected' : '' }}>3 Stars</option>
                                        <option value="4" {{ old('rating', $doctor->doctorDetails->rating ?? '') == '4' ? 'selected' : '' }}>4 Stars</option>
                                        <option value="5" {{ old('rating', $doctor->doctorDetails->rating ?? '') == '5' ? 'selected' : '' }}>5 Stars</option>
                                    </select>
                                    @error('rating')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="image">Profile Image</label>
                                    <div class="custom-file">
                                        <input type="file" name="image" id="image" class="custom-file-input @error('image') is-invalid @enderror">
                                        <label class="custom-file-label" for="image">Choose file...</label>
                                    </div>
                                    @if(isset($doctor->doctorDetails->image) && $doctor->doctorDetails->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/doctors/' . $doctor->doctorDetails->image) }}" class="img-thumbnail" width="150" alt="Doctor Image">
                                        </div>
                                    @endif
                                    @error('image')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Available Slots Section -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h4>Available Slots</h4>
                                <hr>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($avaslot) && count($avaslot) > 0)
                                                @foreach($avaslot as $slot)
                                                <tr>
                                                    <td>{{ $slot->date }}</td>
                                                    <td>{{ $slot->start_time }}</td>
                                                    <td>{{ $slot->end_time }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $slot->is_booked ? 'danger' : 'success' }}">
                                                            {{ $slot->is_booked ? 'Booked' : 'Available' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.slots.edit', $slot->id) }}" class="btn btn-info btn-sm">Edit</a>
                                                        <button type="button" class="btn btn-danger btn-sm" 
                                                                onclick="deleteSlot('{{ $slot->id }}')">Delete</button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No available slots found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addSlotModal">
                                    Add New Slot
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update Doctor Information</button>
                            <a href="{{ route('doctors') }}" class="btn btn-secondary">Cancel</a>
                            <button type="button" class="btn btn-danger float-right" onclick="confirmDelete()">Delete User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Slot Modal -->
<div class="modal fade" id="addSlotModal" tabindex="-1" role="dialog" aria-labelledby="slotModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="slotModalLabel">Add New Available Slot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.slots.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                    
                    <div class="form-group">
                        <label for="slot_date">Date</label>
                        <input type="date" name="date" id="slot_date" class="form-control" required min="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="is_booked">Status</label>
                        <select name="is_booked" id="is_booked" class="form-control" required>
                            <option value="0">Available</option>
                            <option value="1">Booked</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Slot</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Form (hidden) -->
<form id="delete-user-form" action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endif

<script>
    // For file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    
    // Delete slot function
    function deleteSlot(slotId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This slot will be permanently deleted!",
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
                form.action = '{{ url("admin/slots") }}/' + slotId;
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
            text: "This will permanently delete this user, doctor record, and all related data!",
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