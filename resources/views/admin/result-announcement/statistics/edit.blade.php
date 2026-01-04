@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Edit Statistic</h6>
                    <a href="{{ route('admin.result-announcement.statistics.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.result-announcement.statistics.update', $statistic->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" value="{{ old('title', $statistic->title) }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Announcement (optional)</label>
                            <select name="result_announcement_id" class="form-select">
                                <option value="">Select...</option>
                                @foreach($announcements as $a)
                                    <option value="{{ $a->id }}" @selected(old('result_announcement_id', $statistic->result_announcement_id)==$a->id)>{{ $a->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Filters (JSON, optional)</label>
                            <textarea name="filters" rows="4" class="form-control">{{ old('filters', json_encode($statistic->filters, JSON_PRETTY_PRINT)) }}</textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


