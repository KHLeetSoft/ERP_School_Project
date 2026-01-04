@extends('admin.layout.app')

@section('title', 'Teacher Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teacher Details</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('admin.users.teachers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Teachers
            </a>
            <a href="{{ route('admin.users.teachers.edit', 1) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Teacher
            </a>
        </div>
    </div>

    <!-- Teacher Details -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Teacher Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>Name:</th>
                            <td>John Doe</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>john.doe@example.com</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>+1234567890</td>
                        </tr>
                        <tr>
                            <th>Subject:</th>
                            <td>Mathematics</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <th>Qualification:</th>
                            <td>M.Sc Mathematics</td>
                        </tr>
                        <tr>
                            <th>Experience:</th>
                            <td>5 Years</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><span class="badge badge-success">Active</span></td>
                        </tr>
                        <tr>
                            <th>Joined Date:</th>
                            <td>2023-01-15</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <h6 class="font-weight-bold">Address:</h6>
                    <p>123 Main Street, City, State, 12345</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



