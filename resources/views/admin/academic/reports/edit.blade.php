@extends('admin.layout.app')

@section('content')
<div class="container">
    <h3>Edit Report</h3>
    <form method="POST" action="{{ route('admin.academic.reports.update', $report) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ $report->title }}" required></div>
            <div class="col-md-6"><label class="form-label">Date</label><input type="date" class="form-control" name="report_date" value="{{ optional($report->report_date)->format('Y-m-d') }}" required></div>
            <div class="col-md-6"><label class="form-label">Type</label><input class="form-control" name="type" value="{{ $report->type }}"></div>
            <div class="col-md-6"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ $report->status==='draft'?'selected':'' }}>Draft</option>
                    <option value="published" {{ $report->status==='published'?'selected':'' }}>Published</option>
                    <option value="archived" {{ $report->status==='archived'?'selected':'' }}>Archived</option>
                </select>
            </div>
            <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" rows="4" name="description">{{ $report->description }}</textarea></div>
        </div>
        <div class="mt-3">
            <a href="{{ route('admin.academic.reports.index') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
    </div>
@endsection


