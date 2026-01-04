@extends('accountant.layout.app')

@section('title', 'QR Code Management')
@section('page-title', 'QR Code Management')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">QR Code Management</h4>
                            <p class="text-muted mb-0">Generate and manage QR codes for students, payments, and more</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('accountant.qr-codes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Generate QR Code
                            </a>
                            <button class="btn btn-outline-primary" onclick="showBulkQRGeneration()">
                                <i class="fas fa-layer-group me-2"></i>Bulk Generate
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total QR Codes</h6>
                            <h3 class="mb-0">{{ \App\Models\QRCode::where('created_by', auth()->id())->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-qrcode fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Active QR Codes</h6>
                            <h3 class="mb-0">{{ \App\Models\QRCode::where('created_by', auth()->id())->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Scans</h6>
                            <h3 class="mb-0">{{ \App\Models\QRCode::where('created_by', auth()->id())->sum('scan_count') }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-mobile-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">This Month</h6>
                            <h3 class="mb-0">{{ \App\Models\QRCode::where('created_by', auth()->id())->whereMonth('created_at', now()->month)->count() }}</h3>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- QR Codes Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">QR Codes List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="qrCodesTable">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Scans</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#qrCodesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('accountant.qr-codes.datatable') }}",
            type: 'GET'
        },
        columns: [
            { data: 'qr_image', name: 'qr_image', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'type_badge', name: 'type', orderable: false },
            { data: 'status', name: 'status', orderable: false },
            { data: 'scan_count', name: 'scan_count' },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[5, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading QR codes...",
            emptyTable: "No QR codes found",
            zeroRecords: "No QR codes match your search"
        }
    });
});

// Delete QR Code
function deleteQRCode(id) {
    if (confirm('Are you sure you want to delete this QR code?')) {
        $.ajax({
            url: `/accountant/qr-codes/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#qrCodesTable').DataTable().ajax.reload();
                showAlert('success', response.success);
            },
            error: function(xhr) {
                showAlert('error', 'Error deleting QR code');
            }
        });
    }
}

// Show alert
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.content-wrapper').prepend(alertHtml);
    
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection
