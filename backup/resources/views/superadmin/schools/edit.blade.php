@extends('superadmin.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header bg-warning text-white">
            <h4 class="mb-0">Edit School - {{ $school->name }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.schools.update', $school->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">School Name</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $school->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="admin_id" class="form-label">Admin</label>
                        <select name="admin_id" class="form-control">
                            @foreach($availableAdmins  as $admin)
                                <option value="{{ $admin->id }}" {{ $school->admin_id == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }} ({{ $admin->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" name="address" rows="2">{{ old('address', $school->address) }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $school->phone) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $school->email) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="website" class="form-label">Website</label>
                        <input type="text" class="form-control" name="website" value="{{ old('website', $school->website) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="logo" class="form-label">Logo</label>
                        <input type="file" class="form-control" name="logo">
                        @if($school->logo)
                            <img src="{{ asset($school->logo) }}" class="img-thumbnail mt-2" style="height: 80px;">
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Primary Color</label>
                        <input type="color" name="primary_color" class="form-control" value="{{ $school->theme_settings['primary_color'] ?? '#007bff' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Secondary Color</label>
                        <input type="color" name="secondary_color" class="form-control" value="{{ $school->theme_settings['secondary_color'] ?? '#6c757d' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Logo Position</label>
                        <select name="logo_position" class="form-control">
                            <option value="left" {{ ($school->theme_settings['logo_position'] ?? 'left') == 'left' ? 'selected' : '' }}>Left</option>
                            <option value="center" {{ ($school->theme_settings['logo_position'] ?? 'left') == 'center' ? 'selected' : '' }}>Center</option>
                            <option value="right" {{ ($school->theme_settings['logo_position'] ?? 'left') == 'right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>
                </div>


                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $school->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $school->status == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update School</button>
            </form>
        </div>
    </div>
</div>
@endsection
