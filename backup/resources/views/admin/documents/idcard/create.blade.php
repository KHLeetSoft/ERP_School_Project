@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Create ID Card</h3>
    <form method="POST" action="{{ route('admin.documents.idcard.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Student Name</label><input class="form-control" name="student_name" required></div>
            <div class="col-md-3"><label class="form-label">Class</label><input class="form-control" name="class_name"></div>
            <div class="col-md-3"><label class="form-label">Section</label><input class="form-control" name="section_name"></div>
            <div class="col-md-3"><label class="form-label">Roll Number</label><input class="form-control" name="roll_number"></div>
            <div class="col-md-3"><label class="form-label">DOB</label><input type="date" class="form-control" name="date_of_birth"></div>
            <div class="col-md-3"><label class="form-label">Blood Group</label><input class="form-control" name="blood_group"></div>
            <div class="col-md-3"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
            <div class="col-md-6"><label class="form-label">Guardian Name</label><input class="form-control" name="guardian_name"></div>
            <div class="col-md-6"><label class="form-label">Address</label><input class="form-control" name="address"></div>
            <div class="col-md-3"><label class="form-label">Issue Date</label><input type="date" class="form-control" name="issue_date"></div>
            <div class="col-md-3"><label class="form-label">Expiry Date</label><input type="date" class="form-control" name="expiry_date"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.documents.idcard.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
    </div>
@endsection


