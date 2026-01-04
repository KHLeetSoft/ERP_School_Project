@extends('admin.layout.app')

@section('title', 'Error')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Error</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.communications.messages.index') }}">Messages</a></li>
                    <li class="breadcrumb-item active">Error</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    @if(isset($error))
                        <div class="error-icon mb-4">
                            @if($error === 'not_found')
                                <i class="fas fa-exclamation-triangle fa-4x text-warning"></i>
                                <h4 class="mt-3 text-warning">Message Not Found</h4>
                                <p class="text-muted">The message you're looking for doesn't exist or has been deleted.</p>
                            @elseif($error === 'unauthorized')
                                <i class="fas fa-ban fa-4x text-danger"></i>
                                <h4 class="mt-3 text-danger">Access Denied</h4>
                                <p class="text-muted">You don't have permission to view this message.</p>
                            @elseif($error === 'deleted')
                                <i class="fas fa-trash fa-4x text-secondary"></i>
                                <h4 class="mt-3 text-secondary">Message Deleted</h4>
                                <p class="text-muted">This message has been permanently deleted.</p>
                            @else
                                <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
                                <h4 class="mt-3 text-danger">An Error Occurred</h4>
                                <p class="text-muted">{{ $error }}</p>
                            @endif
                        </div>
                    @else
                        <div class="error-icon mb-4">
                            <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
                            <h4 class="mt-3 text-danger">An Error Occurred</h4>
                            <p class="text-muted">Something went wrong. Please try again.</p>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="{{ route('admin.communications.messages.index') }}" class="btn btn-primary me-2">
                            <i class="fas fa-inbox"></i> Back to Inbox
                        </a>
                        <a href="{{ route('admin.communications.messages.dashboard') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-tachometer-alt"></i> Messages Dashboard
                        </a>
                        <a href="{{ route('admin.communications.messages.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Compose New Message
                        </a>
                    </div>

                    @if($suggestions && is_array($suggestions) && count($suggestions) > 0)
                        <div class="mt-5">
                            <h5>You might also be interested in:</h5>
                            <div class="row mt-3">
                                @foreach($suggestions as $suggestion)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $suggestion->subject }}</h6>
                                                <p class="card-text text-muted">
                                                    {{ Str::limit(strip_tags($suggestion->body), 100) }}
                                                </p>
                                                <a href="{{ route('admin.communications.messages.show', $suggestion->id) }}" class="btn btn-sm btn-outline-primary">
                                                    View Message
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
