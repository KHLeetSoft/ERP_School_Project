@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Syllabus Details</h4>
    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Subject</dt>
                <dd class="col-sm-9">{{ optional($syllabus->subject)->name }} ({{ optional($syllabus->subject)->code }})</dd>
                <dt class="col-sm-3">Term</dt>
                <dd class="col-sm-9">{{ $syllabus->term ?? '-' }}</dd>
                <dt class="col-sm-3">Title</dt>
                <dd class="col-sm-9">{{ $syllabus->title }}</dd>
                <dt class="col-sm-3">Description</dt>
                <dd class="col-sm-9">{{ $syllabus->description }}</dd>
                <dt class="col-sm-3">Total Units</dt>
                <dd class="col-sm-9">{{ $syllabus->total_units ?? '-' }}</dd>
                <dt class="col-sm-3">Completed Units</dt>
                <dd class="col-sm-9">{{ $syllabus->completed_units ?? 0 }}</dd>
                <dt class="col-sm-3">Progress</dt>
                <dd class="col-sm-9">{{ $syllabus->progress_percent }}%</dd>
                <dt class="col-sm-3">Start Date</dt>
                <dd class="col-sm-9">{{ optional($syllabus->start_date)->format('Y-m-d') }}</dd>
                <dt class="col-sm-3">End Date</dt>
                <dd class="col-sm-9">{{ optional($syllabus->end_date)->format('Y-m-d') }}</dd>
                <dt class="col-sm-3">Status</dt>
                <dd class="col-sm-9">{{ $syllabus->status ? 'Active' : 'Inactive' }}</dd>
            </dl>
            <a href="{{ route('admin.academic.syllabus.edit', $syllabus) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.academic.syllabus.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection


