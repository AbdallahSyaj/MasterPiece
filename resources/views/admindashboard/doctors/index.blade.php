@extends('admindashboard.layout')

@section('title' , 'List of Doctors')

@section('contant')
<!-- Main Content -->


    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">List Of Doctors</h1>
        <p class="mb-4">Manage and view detailed information about registered doctors, including their specialties, availability. </p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Table of Doctors</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Specialization</th>
                                <th>City</th>
                                <th>Rate/5</th>
                                <th>Start date</th>
                                <th>More Info</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Specialization</th>
                                <th>City</th>
                                <th>Rate/5</th>
                                <th>Start date</th>
                                <th>More Info</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($doctors as $doctor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $doctor->user->name ?? 'N/A' }}</td>
                                <td>{{ $doctor->doctorDetails->specialty ?? 'N/A' }}</td>
                                <td>{{ $doctor->doctorDetails->city ?? 'N/A' }}</td>
                                <td>{{ $doctor->doctorDetails->rating ?? 'N/A' }}</td>
                                <td>{{ $doctor->doctorDetails->created_at ?? 'N/A' }}</td>
                                <td><button class="btn btn-success" onclick="window.location.href='{{ route('admin.doctors.edit', $doctor->id) }}'">Edit doctor</button></td>
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