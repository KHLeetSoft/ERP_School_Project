@extends('admin.layout.app')

@section('title', 'Accountant Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Accountant Details</h4>
                    <div>
                        <a href="{{ route('admin.users.accountants.edit', $accountant->id) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.users.accountants.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Name</h6>
                            <p>{{ $accountant->name }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Email</h6>
                            <p>{{ $accountant->email }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Status</h6>
                            <p>
                                @if($accountant->status)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Created At</h6>
                            <p>{{ $accountant->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection