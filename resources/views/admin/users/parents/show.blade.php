@extends('admin.layout.app')

@section('title', 'Parent Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Parent Details</h4>
                    <div>
                        <a href="{{ route('admin.users.parents.edit', $parent->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.users.parents.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5>Name</h5>
                            <p>{{ $parent->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5>Email</h5>
                            <p>{{ $parent->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5>Status</h5>
                            <p>
                                @if($parent->status)
                                    <span class="badge badge-pill badge-light-success">Active</span>
                                @else
                                    <span class="badge badge-pill badge-light-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h5>Created At</h5>
                            <p>{{ $parent->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 