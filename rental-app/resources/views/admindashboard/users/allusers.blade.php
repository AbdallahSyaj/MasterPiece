@extends('admindashboard.layout')

@section('title' , 'List of Users')

@section('contant')
<!-- Main Content -->
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">List Of All Users</h1>
        <p class="mb-4">Manage and view detailed information about all registered users, including patients and doctors.</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Data Table of Users</h6>
                <button class="btn-add-user" onclick="window.location.href='{{ route('admin.users.create') }}'">Add User</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$user->name ?? 'N/A'}}</td>
                                <td>{{$user->email ?? 'N/A'}}</td>
                                <td>{{$user->role ?? 'N/A'}}</td>
                                <td>{{$user->created_at->format('Y-m-d') ?? 'N/A'}}</td>
                                <td>
                                    <div class="d-flex">
                                        @if($user->role == 'patient')
                                            @php $patient = App\Models\admin\Patient::where('user_id', $user->id)->first(); @endphp
                                            @if($patient)
                                                <a href="{{ route('admin.patients.edit', $patient->id) }}" class="btn btn-success btn-sm mr-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this patient?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($user->role == 'doctor')
                                            @php $doctor = App\Models\admin\Doctor::where('user_id', $user->id)->first(); @endphp
                                            @if($doctor)
                                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-success btn-sm mr-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this doctor?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <button class="btn btn-secondary btn-sm disabled mr-1">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
</div>
@endsection