@extends('admin.layout.app')

@section('title', 'Call Log Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Call Log Details</h1>
        <div class="d-none d-sm-inline-block">
            <a href="{{ route('admin.office.calllogs.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Call Logs
            </a>
        </div>
    </div>

    <!-- Call Log Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Call Information</h6>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.office.calllogs.edit', $log->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <button class="btn btn-danger btn-sm" onclick="deleteCallLog({{ $log->id }})">
                    <i class="fas fa-trash me-1"></i>Delete
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">Caller Information</h6>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Name:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->caller_name }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Phone:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->phone ?: 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">Call Details</h6>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Purpose:</strong>
                            </div>
                            <div class="col-sm-8">
                                @if($log->purpose)
                                    <span class="badge bg-info">{{ $log->purpose }}</span>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Duration:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->duration ?: 'Not recorded' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">Date & Time</h6>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Date:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->date ? $log->date->format('M d, Y') : 'Not specified' }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Time:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->time ?: 'Not specified' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">Record Information</h6>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Created:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Updated:</strong>
                            </div>
                            <div class="col-sm-8">
                                {{ $log->updated_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($log->note)
            <div class="row">
                <div class="col-12">
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">Notes</h6>
                        <div class="bg-light p-3 rounded">
                            {{ $log->note }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <a href="{{ route('admin.office.calllogs.edit', $log->id) }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit Call Log
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.office.calllogs.create') }}" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-plus me-2"></i>Add New Call
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.office.calllogs.index') }}" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-list me-2"></i>View All Calls
                    </a>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-warning w-100 mb-2" onclick="printCallLog()">
                        <i class="fas fa-print me-2"></i>Print Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this call log? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function deleteCallLog(callLogId) {
    $('#confirmDelete').data('id', callLogId);
    $('#deleteModal').modal('show');
}

$('#confirmDelete').click(function() {
    var callLogId = $(this).data('id');
    $.ajax({
        url: "{{ route('admin.office.calllogs.destroy', '') }}/" + callLogId,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#deleteModal').modal('hide');
            toastr.success('Call log deleted successfully');
            setTimeout(function() {
                window.location.href = "{{ route('admin.office.calllogs.index') }}";
            }, 1500);
        },
        error: function() {
            toastr.error('Error deleting call log');
        }
    });
});

function printCallLog() {
    window.print();
}

// Print styles
$(document).ready(function() {
    $('head').append(`
        <style media="print">
            .btn, .card-header .d-flex, .card:last-child {
                display: none !important;
            }
            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
            .container-fluid {
                max-width: 100% !important;
            }
        </style>
    `);
});
</script>
@endpush
@endsection