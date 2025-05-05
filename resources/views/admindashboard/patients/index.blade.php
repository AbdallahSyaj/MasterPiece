@extends('admindashboard.layout')

@section('title' , 'List of Patients')

@section('contant')
<!-- Main Content -->
    

    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">List Of Patients</h1>
        <p class="mb-4">Manage and view detailed information about registered patients, including their medical history, appointments, and treatment progress.</p>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Table of Patients</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>gender</th>
                                <th>Start date</th>
                                <th>More Info</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>gender</th>
                                <th>Start date</th>
                                <th>More Info</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($patients as $patient)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$patient ->user->name ?? 'N/A'}}</td>
                                <td>{{$patient ->user->email ?? 'N/A'}}</td>
                                <td>{{$patient->gender ?? 'N/A'}}</td>
                                <td>{{$patient->created_at ?? 'N/A'}}</td>
                                <td><button class="btn btn-success" onclick="window.location.href='{{ route('admin.patients.edit', $patient->id) }}'">Edit Patient</button></td>
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