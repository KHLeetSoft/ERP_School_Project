@extends('admin.layout.app')

@section('title', 'Newsletter Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Newsletter Details</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.newsletter.index') }}">Newsletters</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ul>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.communications.newsletter.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
                <a href="{{ route('admin.communications.newsletter.preview', $newsletter->id) }}" class="btn btn-info">
                    <i class="fas fa-eye mr-1"></i> Preview
                </a>
                <a href="{{ route('admin.communications.newsletter.edit', $newsletter->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
            </div>
        </div>
    </div>

    <!-- Newsletter Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Main Content -->
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-newspaper mr-2"></i>
                        Newsletter Information
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Title:</label>
                                <p class="form-control-static">{{ $newsletter->title }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Subject:</label>
                                <p class="form-control-static">{{ $newsletter->subject }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Category:</label>
                                <p class="form-control-static">
                                    <span class="badge badge-info">{{ ucfirst($newsletter->category) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Status:</label>
                                <p class="form-control-static">
                                    <span class="badge badge-{{ $newsletter->status === 'sent' ? 'success' : ($newsletter->status === 'scheduled' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($newsletter->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Content:</label>
                        <div class="content-preview">
                            {!! $newsletter->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt mr-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.communications.newsletter.edit', $newsletter->id) }}" 
                       class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>

                    <form action="{{ route('admin.communications.newsletter.destroy', $newsletter->id) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-block" 
                                onclick="return confirm('Are you sure you want to delete this newsletter?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-preview {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 20px;
    max-height: 400px;
    overflow-y: auto;
}

.form-control-static {
    padding: 8px 0;
    margin-bottom: 0;
    color: #495057;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
}
</style>
@endsection
