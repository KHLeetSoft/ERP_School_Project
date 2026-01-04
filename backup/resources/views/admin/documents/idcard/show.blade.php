@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>ID Card #{{ $idcard->id }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.documents.idcard.edit', $idcard) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.documents.idcard.destroy', $idcard) }}" method="POST" onsubmit="return confirm('Delete this ID Card?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>Student:</strong><div>{{ $idcard->student_name }}</div></div>
                <div class="col-md-3"><strong>Class:</strong><div>{{ $idcard->class_name ?? '-' }}</div></div>
                <div class="col-md-3"><strong>Section:</strong><div>{{ $idcard->section_name ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Roll:</strong><div>{{ $idcard->roll_number ?? '-' }}</div></div>
                <div class="col-md-3"><strong>DOB:</strong><div>{{ optional($idcard->date_of_birth)->format('Y-m-d') }}</div></div>
                <div class="col-md-3"><strong>Blood:</strong><div>{{ $idcard->blood_group ?? '-' }}</div></div>
                <div class="col-md-3"><strong>Phone:</strong><div>{{ $idcard->phone ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Guardian:</strong><div>{{ $idcard->guardian_name ?? '-' }}</div></div>
                <div class="col-md-6"><strong>Address:</strong><div>{{ $idcard->address ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Issue:</strong><div>{{ optional($idcard->issue_date)->format('Y-m-d') }}</div></div>
                <div class="col-md-3"><strong>Expiry:</strong><div>{{ optional($idcard->expiry_date)->format('Y-m-d') }}</div></div>
                <div class="col-md-3"><strong>Status:</strong><div>{{ ucfirst($idcard->status) }}</div></div>
            </div>
            <a href="{{ route('admin.documents.idcard.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
    </div>
@endsection


