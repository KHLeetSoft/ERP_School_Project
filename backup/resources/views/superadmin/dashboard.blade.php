@extends('superadmin.app')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">🎯 SuperAdmin Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Summary Cards -->
            <div class="row">
                <x-dashboard-box count="{{ $totalSchools }}" label="Total Schools" icon="bxs-school" bg="info" link="{{ route('superadmin.schools.index') }}" />
                <x-dashboard-box count="{{ $activeSchools }}" label="Active Schools" icon="bx-check-circle" bg="success" link="{{ route('superadmin.schools.index') }}" />
                <x-dashboard-box count="{{ $totalAdmins }}" label="School Admins" icon="bx-user-circle" bg="warning" link="{{ route('superadmin.admins.index') }}" />
                <x-dashboard-box count="{{ $totalUsers }}" label="Total Users" icon="bx-user" bg="danger" link="#" />
            </div>

            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-md-12 text-center">
                    <h2>Welcome, {{ Auth::user()->name }}</h2>
                    <p class="lead">Role: <span class="badge badge-success">{{ Auth::user()->role }}</span></p>
                    <p>Email: {{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Recent Tables -->
            <div class="row">
                <div class="col-md-6">
                    @include('superadmin.dashboard.recent_schools', ['recentSchools' => $recentSchools])
                </div>
                <div class="col-md-6">
                    @include('superadmin.dashboard.recent_admins', ['recentAdmins' => $recentAdmins])
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
