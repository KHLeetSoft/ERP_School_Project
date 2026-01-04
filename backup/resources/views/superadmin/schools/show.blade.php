@extends('superadmin.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">School Details</h4>
            <a href="{{ route('superadmin.schools.index') }}" class="btn btn-light btn-sm">← Back to List</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4 text-center">
                    <img src="{{ asset($school->logo ?? 'default-logo.png') }}" alt="Logo" class="img-thumbnail" style="max-height: 150px;">
                </div>
                <div class="col-md-8">
                    <h5 class="mb-3">{{ $school->name }}</h5>
                    <p><strong>Email:</strong> {{ $school->email }}</p>
                    <p><strong>Phone:</strong> {{ $school->phone }}</p>
                    <p><strong>Website:</strong> <a href="{{ $school->website }}" target="_blank">{{ $school->website }}</a></p>
                    <p><strong>Admin:</strong> {{ $school->admin->name ?? 'N/A' }}</p>
                    <p><strong>Address:</strong> {{ $school->address }}</p>
                </div>
            </div>

            <div class="bg-light p-3 rounded">
                <h6>Theme Settings</h6>
                @if($school->theme_settings)
                    <p><strong>Primary Color:</strong> <span style="background-color: {{ $school->theme_settings['primary_color'] ?? '#007bff' }}; padding: 5px 10px; border-radius: 5px; color: white;">{{ $school->theme_settings['primary_color'] ?? '#007bff' }}</span></p>
                    <p><strong>Secondary Color:</strong> <span style="background-color: {{ $school->theme_settings['secondary_color'] ?? '#6c757d' }}; padding: 5px 10px; border-radius: 5px; color: white;">{{ $school->theme_settings['secondary_color'] ?? '#6c757d' }}</span></p>
                    <p><strong>Logo Position:</strong> {{ ucfirst($school->theme_settings['logo_position'] ?? 'left') }}</p>
                @else
                    <p class="text-muted">No theme settings configured</p>
                @endif
            </div>
        </div>
        <div class="card-footer text-muted text-end">
            <small>Created at: {{ $school->created_at->format('d M Y') }}</small>
        </div>
    </div>
</div>
@endsection
