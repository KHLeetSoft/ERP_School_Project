@extends('admin.layout.app')

@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Student Health Record</h4>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <!-- Left: Student Info -->
            <div class="col-md-9">
                <h5 class="text-secondary">
                    {{ $health->student->first_name ?? '-' }} {{ $health->student->last_name ?? '-' }}
                </h5>
                <p class="text-muted mb-1">
                    Class: {{ $health->class->name ?? 'N/A' }}
                </p>
            </div>

            <!-- Right: Profile Image -->
            <div class="col-md-3 text-md-end text-center">
                @if(!empty($health->student->profile_image))
                    <img src="{{ asset('storage/' . $health->student->profile_image) }}" 
                         alt="Profile Image" 
                         class="img-thumbnail" 
                         style="width:120px; height:120px; object-fit:cover;">
                @else
                    <img src="{{ asset('images/default-profile.png') }}" 
                         alt="Default Image" 
                         class="img-thumbnail" 
                         style="width:120px; height:120px; object-fit:cover;">
                @endif
            </div>
        </div>

        <!-- Health Info Table -->
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th>Height</th>
                    <td>{{ $health->height_cm ?? '-' }} cm</td>
                </tr>
                <tr>
                    <th>Weight</th>
                    <td>{{ $health->weight_kg ?? '-' }} kg</td>
                </tr>
                <tr>
                    <th>Blood Group</th>
                    <td>{{ $health->blood_group ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Allergies</th>
                    <td>{{ $health->allergies ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Medical Conditions</th>
                    <td>{{ $health->medical_conditions ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Immunizations</th>
                    <td>{{ $health->immunizations ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Last Checkup Date</th>
                    <td>
                        {{ $health->last_checkup_date 
                            ? \Carbon\Carbon::parse($health->last_checkup_date)->format('d M, Y') 
                            : '-' 
                        }}
                    </td>
                </tr>
                <tr>
                    <th>Health Notes</th>
                    <td>{{ $health->notes ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('admin.students.health.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
