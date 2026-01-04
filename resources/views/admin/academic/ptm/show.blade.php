@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>PTM #{{ $ptm->id }}</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.academic.ptm.edit', $ptm) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.academic.ptm.destroy', $ptm) }}" method="POST" onsubmit="return confirm('Delete this PTM?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>Title:</strong><div>{{ $ptm->title }}</div></div>
                <div class="col-md-3"><strong>Date:</strong><div>{{ optional($ptm->date)->format('Y-m-d') }}</div></div>
                <div class="col-md-3"><strong>Status:</strong><div>{{ ucfirst($ptm->status) }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Start Time:</strong><div>{{ optional($ptm->start_time)->format('Y-m-d H:i') }}</div></div>
                <div class="col-md-6"><strong>End Time:</strong><div>{{ optional($ptm->end_time)->format('Y-m-d H:i') }}</div></div>
            </div>
            <div class="mb-3"><strong>Description:</strong><div>{{ $ptm->description ?? '-' }}</div></div>
            <a href="{{ route('admin.academic.ptm.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection


