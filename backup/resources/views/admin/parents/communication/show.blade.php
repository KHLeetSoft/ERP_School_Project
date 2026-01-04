@extends('admin.layout.app')

@section('title', 'Communication Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Communication Details</h4>
                    <div>
                        <a href="{{ route('admin.parents.communication.edit', $communication->id) }}" class="btn btn-primary me-2">
                            <i class="bx bxs-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.parents.communication.resend', $communication->id) }}" class="btn btn-warning me-2">
                            <i class="bx bx-refresh"></i> Resend
                        </a>
                        <a href="{{ route('admin.parents.communication.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Communication Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Communication ID</label>
                                    <p class="form-control-plaintext">#{{ $communication->id }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Type</label>
                                    <p class="form-control-plaintext">
                                        <i class="{{ $communication->communication_type_icon }}"></i>
                                        {{ ucfirst($communication->communication_type) }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $communication->status_badge }}">
                                            {{ ucfirst($communication->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Priority</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge {{ $communication->priority_badge }}">
                                            {{ ucfirst($communication->priority) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Category</label>
                                    <p class="form-control-plaintext">
                                        {{ $communication->category ? ucfirst($communication->category) : 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Subject</label>
                                    <p class="form-control-plaintext">
                                        {{ $communication->subject ?: 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Message -->
                            <div class="mb-3">
                                <label class="form-label fw-bold">Message</label>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($communication->message)) !!}
                                </div>
                            </div>

                            <!-- Response -->
                            @if($communication->response)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Parent Response</label>
                                <div class="border rounded p-3 bg-success bg-opacity-10">
                                    {!! nl2br(e($communication->response)) !!}
                                </div>
                            </div>
                            @endif

                            <!-- Notes -->
                            @if($communication->notes)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes</label>
                                <p class="form-control-plaintext">{{ $communication->notes }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Sidebar Information -->
                        <div class="col-md-4">
                            <!-- Parent Information -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bx bx-user"></i> Parent Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ $communication->parentDetail->primary_contact_name ?? $communication->parentDetail->user->name ?? 'N/A' }}</p>
                                    <p><strong>Phone:</strong> {{ $communication->parentDetail->phone_primary ?: 'N/A' }}</p>
                                    <p><strong>Email:</strong> {{ $communication->parentDetail->email_primary ?: 'N/A' }}</p>
                                    <p><strong>Address:</strong> {{ $communication->parentDetail->address ?: 'N/A' }}</p>
                                </div>
                            </div>

                            <!-- Student Information -->
                            @if($communication->student)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bx bx-book"></i> Student Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ $communication->student->first_name }} {{ $communication->student->last_name }}</p>
                                    <p><strong>Class:</strong> {{ $communication->student->class ?? 'N/A' }}</p>
                                    <p><strong>Section:</strong> {{ $communication->student->section ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Admin Information -->
                            @if($communication->admin)
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bx bx-shield"></i> Admin Information</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ $communication->admin->name }}</p>
                                    <p><strong>Email:</strong> {{ $communication->admin->email }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Communication Details -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bx bx-info-circle"></i> Communication Details</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Channel:</strong> {{ $communication->communication_channel ?: 'N/A' }}</p>
                                    <p><strong>Cost:</strong> {{ $communication->cost ? '$' . number_format($communication->cost, 2) : 'N/A' }}</p>
                                    <p><strong>Created:</strong> {{ $communication->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>

                            <!-- Timestamps -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="bx bx-time"></i> Timestamps</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Sent:</strong> {{ $communication->sent_at ? $communication->sent_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    <p><strong>Delivered:</strong> {{ $communication->delivered_at ? $communication->delivered_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    <p><strong>Read:</strong> {{ $communication->read_at ? $communication->read_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    @if($communication->response_at)
                                    <p><strong>Response:</strong> {{ $communication->response_at->format('M d, Y H:i') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <div class="d-flex justify-content-between">
                                <div>
                                    @if($communication->status !== 'read')
                                    <button class="btn btn-success me-2" onclick="markAsRead({{ $communication->id }})">
                                        <i class="bx bx-check"></i> Mark as Read
                                    </button>
                                    @endif
                                    
                                    @if($communication->status === 'failed')
                                    <button class="btn btn-warning me-2" onclick="retryCommunication({{ $communication->id }})">
                                        <i class="bx bx-refresh"></i> Retry
                                    </button>
                                    @endif
                                </div>
                                
                                <div>
                                    <button class="btn btn-danger" onclick="deleteCommunication({{ $communication->id }})">
                                        <i class="bx bx-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function markAsRead(id) {
    if (confirm('Mark this communication as read?')) {
        $.ajax({
            url: '{{ route("admin.parents.communication.mark-read", ":id") }}'.replace(':id', id),
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                showAlert('success', response.message);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function() {
                showAlert('error', 'An error occurred while marking as read.');
            }
        });
    }
}

function retryCommunication(id) {
    if (confirm('Retry this failed communication?')) {
        window.location.href = '{{ route("admin.parents.communication.resend", ":id") }}'.replace(':id', id);
    }
}

function deleteCommunication(id) {
    if (confirm('Are you sure you want to delete this communication? This action cannot be undone.')) {
        $.ajax({
            url: '{{ url("admin/parents/communication") }}/' + id,
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                showAlert('success', response.message);
                setTimeout(function() {
                    window.location.href = '{{ route("admin.parents.communication.index") }}';
                }, 1500);
            },
            error: function() {
                showAlert('error', 'An error occurred while deleting the communication.');
            }
        });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.card-body').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection
