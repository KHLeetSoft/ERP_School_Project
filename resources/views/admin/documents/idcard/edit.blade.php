@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Edit ID Card</h3>
    <form method="POST" action="{{ route('admin.documents.idcard.update', $idcard) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Student Name</label><input class="form-control" name="student_name" value="{{ $idcard->student_name }}" required></div>
            <div class="col-md-3"><label class="form-label">Class</label><input class="form-control" name="class_name" value="{{ $idcard->class_name }}"></div>
            <div class="col-md-3"><label class="form-label">Section</label><input class="form-control" name="section_name" value="{{ $idcard->section_name }}"></div>
            <div class="col-md-3"><label class="form-label">Roll Number</label><input class="form-control" name="roll_number" value="{{ $idcard->roll_number }}"></div>
            <div class="col-md-3"><label class="form-label">DOB</label><input type="date" class="form-control" name="date_of_birth" value="{{ optional($idcard->date_of_birth)->format('Y-m-d') }}"></div>
            <div class="col-md-3"><label class="form-label">Blood Group</label><input class="form-control" name="blood_group" value="{{ $idcard->blood_group }}"></div>
            <div class="col-md-3"><label class="form-label">Phone</label><input class="form-control" name="phone" value="{{ $idcard->phone }}"></div>
            <div class="col-md-6"><label class="form-label">Guardian Name</label><input class="form-control" name="guardian_name" value="{{ $idcard->guardian_name }}"></div>
            <div class="col-md-6"><label class="form-label">Address</label><input class="form-control" name="address" value="{{ $idcard->address }}"></div>
            <div class="col-md-3"><label class="form-label">Issue Date</label><input type="date" class="form-control" name="issue_date" value="{{ optional($idcard->issue_date)->format('Y-m-d') }}"></div>
            <div class="col-md-3"><label class="form-label">Expiry Date</label><input type="date" class="form-control" name="expiry_date" value="{{ optional($idcard->expiry_date)->format('Y-m-d') }}"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active" {{ $idcard->status==='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ $idcard->status==='inactive'?'selected':'' }}>Inactive</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.documents.idcard.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
    </div>
@endsection


