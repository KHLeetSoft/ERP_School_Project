@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Edit Transfer Certificate</h3>
    <form method="POST" action="{{ route('admin.documents.transfer-certificate.update', $tc) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Student Name</label><input class="form-control" name="student_name" value="{{ $tc->student_name }}" required></div>
            <div class="col-md-3"><label class="form-label">Admission No</label><input class="form-control" name="admission_no" value="{{ $tc->admission_no }}"></div>
            <div class="col-md-3"><label class="form-label">TC Number</label><input class="form-control" name="tc_number" value="{{ $tc->tc_number }}"></div>
            <div class="col-md-3"><label class="form-label">Class</label><input class="form-control" name="class_name" value="{{ $tc->class_name }}"></div>
            <div class="col-md-3"><label class="form-label">Section</label><input class="form-control" name="section_name" value="{{ $tc->section_name }}"></div>
            <div class="col-md-3"><label class="form-label">DOB</label><input type="date" class="form-control" name="date_of_birth" value="{{ optional($tc->date_of_birth)->format('Y-m-d') }}"></div>
            <div class="col-md-3"><label class="form-label">Admission Date</label><input type="date" class="form-control" name="admission_date" value="{{ optional($tc->admission_date)->format('Y-m-d') }}"></div>
            <div class="col-md-3"><label class="form-label">Leaving Date</label><input type="date" class="form-control" name="leaving_date" value="{{ optional($tc->leaving_date)->format('Y-m-d') }}"></div>
            <div class="col-md-6"><label class="form-label">Father's Name</label><input class="form-control" name="father_name" value="{{ $tc->father_name }}"></div>
            <div class="col-md-6"><label class="form-label">Mother's Name</label><input class="form-control" name="mother_name" value="{{ $tc->mother_name }}"></div>
            <div class="col-md-6"><label class="form-label">Reason For Leaving</label><input class="form-control" name="reason_for_leaving" value="{{ $tc->reason_for_leaving }}"></div>
            <div class="col-md-3"><label class="form-label">Conduct</label><input class="form-control" name="conduct" value="{{ $tc->conduct }}"></div>
            <div class="col-md-3"><label class="form-label">Issue Date</label><input type="date" class="form-control" name="issue_date" value="{{ optional($tc->issue_date)->format('Y-m-d') }}"></div>
            <div class="col-12"><label class="form-label">Remarks</label><input class="form-control" name="remarks" value="{{ $tc->remarks }}"></div>
            <div class="col-md-3"><label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="draft" @selected($tc->status==='draft')>Draft</option>
                    <option value="issued" @selected($tc->status==='issued')>Issued</option>
                    <option value="cancelled" @selected($tc->status==='cancelled')>Cancelled</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.documents.transfer-certificate.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


