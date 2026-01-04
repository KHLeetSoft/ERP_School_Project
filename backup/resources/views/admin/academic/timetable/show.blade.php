@extends('admin.layout.app')

@section('title', 'View Timetable')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Timetable Details</h5>
        <div>
            <a href="{{ route('admin.academic.timetable.export', 'pdf') }}" class="btn btn-sm btn-danger me-1">
                <i class="bx bxs-file-pdf"></i> PDF
            </a>
            <a href="{{ route('admin.academic.timetable.export', 'excel') }}" class="btn btn-sm btn-success">
                <i class="bx bxs-file-export"></i> Excel
            </a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Class</th>
                <td>{{ $timetable->class->name }}</td>
            </tr>
            <tr>
                <th>Section</th>
                <td>{{ $timetable->section->name }}</td>
            </tr>
            <tr>
                <th>Subject</th>
                <td>{{ $timetable->subject->name }}</td>
            </tr>
            <tr>
                <th>Teacher</th>
                <td>{{ $timetable->teacher->name }}</td>
            </tr>
            <tr>
                <th>Time Slot</th>
                <td>{{ date('h:i A', strtotime($timetable->start_time)) }} - {{ date('h:i A', strtotime($timetable->end_time)) }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-{{ $timetable->status == 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($timetable->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('admin.academic.timetable.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back
        </a>
        <a href="{{ route('admin.academic.timetable.edit', $timetable->id) }}" class="btn btn-primary">
            <i class="bx bxs-edit"></i> Edit
        </a>
    </div>
</div>
@endsection
