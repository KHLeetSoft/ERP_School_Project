@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Transfer Certificate #{{ $tc->id }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.documents.transfer-certificate.edit', $tc) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.documents.transfer-certificate.print', $tc) }}" target="_blank" class="btn btn-outline-secondary">Print</a>
            <form action="{{ route('admin.documents.transfer-certificate.destroy', $tc) }}" method="POST" onsubmit="return confirm('Delete this TC?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>Student:</strong><div>{{ $tc->student_name }}</div></div>
                <div class="col-md-3"><strong>Admission No:</strong><div>{{ $tc->admission_no ?? '-' }}</div></div>
                <div class="col-md-3"><strong>TC No:</strong><div>{{ $tc->tc_number ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Class:</strong><div>{{ $tc->class_name ?? '-' }}</div></div>
                <div class="col-md-3"><strong>Section:</strong><div>{{ $tc->section_name ?? '-' }}</div></div>
                <div class="col-md-3"><strong>DOB:</strong><div>{{ optional($tc->date_of_birth)->format('Y-m-d') }}</div></div>
                <div class="col-md-3"><strong>Issue Date:</strong><div>{{ optional($tc->issue_date)->format('Y-m-d') }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Father's Name:</strong><div>{{ $tc->father_name ?? '-' }}</div></div>
                <div class="col-md-6"><strong>Mother's Name:</strong><div>{{ $tc->mother_name ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Admission Date:</strong><div>{{ optional($tc->admission_date)->format('Y-m-d') }}</div></div>
                <div class="col-md-4"><strong>Leaving Date:</strong><div>{{ optional($tc->leaving_date)->format('Y-m-d') }}</div></div>
                <div class="col-md-4"><strong>Conduct:</strong><div>{{ $tc->conduct ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12"><strong>Reason For Leaving:</strong><div>{{ $tc->reason_for_leaving ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12"><strong>Remarks:</strong><div>{{ $tc->remarks ?? '-' }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3"><strong>Status:</strong><div>{{ ucfirst($tc->status) }}</div></div>
            </div>
        </div>
    </div>
</div>
@endsection


