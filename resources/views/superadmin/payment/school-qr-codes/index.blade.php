@extends('superadmin.app')

@section('title', 'School QR Code Management')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="qr-icon me-3">
                            <i class="bx bx-qr-scan fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">School QR Code Management</h1>
                            <p class="text-muted mb-0">Manage payment QR codes for all schools</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.payment.school-qr-codes.create') }}" class="btn btn-primary btn-lg">
                            <i class="bx bx-plus me-2"></i>Generate QR Code
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="totalQrCodes">0</h3>
                                    <p class="mb-0">Total QR Codes</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-qr-scan fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="activeQrCodes">0</h3>
                                    <p class="mb-0">Active QR Codes</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-check-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="inactiveQrCodes">0</h3>
                                    <p class="mb-0">Inactive QR Codes</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-pause-circle fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card stats-card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h3 class="mb-0" id="totalSchools">0</h3>
                                    <p class="mb-0">Schools with QR</p>
                                </div>
                                <div class="stats-icon">
                                    <i class="bx bx-building fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <label for="schoolFilter" class="form-label fw-bold">Filter by School:</label>
                                    <select class="form-select" id="schoolFilter">
                                        <option value="">All Schools</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}">{{ $school->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="statusFilter" class="form-label fw-bold">Filter by Status:</label>
                                    <select class="form-select" id="statusFilter">
                                        <option value="">All Status</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-2 mt-4">
                                        <button class="btn btn-outline-secondary" id="refreshTable">
                                            <i class="bx bx-refresh me-1"></i>Refresh
                                        </button>
                                        <button class="btn btn-outline-success" id="exportData">
                                            <i class="bx bx-export me-1"></i>Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- QR Codes Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-qr-scan me-2"></i>School QR Codes
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="school-qr-codes-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-semibold text-dark">#</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-building me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">School</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-qr-scan me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">QR Code Info</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-image me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">QR Code</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-rupee me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">Amount</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-bar-chart me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">Usage</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="bx bx-calendar me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">Created</span>
                                                </div>
                                            </th>
                                            <th class="border-0 py-3 px-4 text-center">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <i class="bx bx-cog me-2 text-primary"></i>
                                                    <span class="fw-semibold text-dark">Actions</span>
                                                </div>
                                            </th>
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
    </section>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bx bx-trash me-2"></i>Delete QR Code
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h5>Are you sure you want to delete this QR code?</h5>
                <p class="text-muted">This action cannot be undone. The QR code image will also be deleted.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="bx bx-trash me-1"></i>Delete QR Code
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.qr-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.stats-card {
    border-radius: 15px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-icon {
    opacity: 0.3;
}

.table th {
    background-color: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    border: none;
    vertical-align: middle;
    padding: 1rem;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.btn-action {
    padding: 0.5rem;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
}

.badge-light-success {
    background-color: #d4edda;
    color: #155724;
}

.badge-light-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.img-thumbnail {
    border-radius: 8px;
    border: 2px solid #e9ecef;
}
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#school-qr-codes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('superadmin.payment.school-qr-codes.index') }}",
            data: function(d) {
                d.school_id = $('#schoolFilter').val();
                d.status = $('#statusFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center fw-bold text-muted' },
            { data: 'school_info', name: 'school_info', orderable: false },
            { data: 'qr_info', name: 'qr_info', orderable: false },
            { data: 'qr_code', name: 'qr_code', orderable: false, className: 'text-center' },
            { data: 'amount_info', name: 'amount_info', orderable: false, className: 'text-center' },
            { data: 'usage_stats', name: 'usage_stats', orderable: false, className: 'text-center' },
            { data: 'created_at', name: 'created_at', className: 'text-center' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[6, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading QR codes...",
            emptyTable: "No QR codes found",
            zeroRecords: "No matching QR codes found"
        },
        drawCallback: function() {
            loadStats();
        }
    });

    // Load statistics
    function loadStats() {
        $.ajax({
            url: "{{ route('superadmin.payment.school-qr-codes.index') }}",
            data: {
                stats_only: true,
                school_id: $('#schoolFilter').val(),
                status: $('#statusFilter').val()
            },
            success: function(data) {
                // Update stats cards
                $('#totalQrCodes').text(data.total || 0);
                $('#activeQrCodes').text(data.active || 0);
                $('#inactiveQrCodes').text(data.inactive || 0);
                $('#totalSchools').text(data.schools || 0);
            }
        });
    }

    // Filter change events
    $('#schoolFilter, #statusFilter').on('change', function() {
        table.ajax.reload();
    });

    // Refresh table
    $('#refreshTable').on('click', function() {
        table.ajax.reload();
    });

    // Export data
    $('#exportData').on('click', function() {
        // Implementation for export functionality
        showAlert('Export functionality will be implemented soon!', 'info');
    });

    // Toggle status
    $(document).on('click', '.toggle-status-btn', function() {
        var qrCodeId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: "{{ route('superadmin.payment.school-qr-codes.toggle-status', '') }}/" + qrCodeId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    showAlert(response.message, 'success');
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('Something went wrong!', 'error');
            }
        });
    });

    // Delete QR code
    var deleteQrCodeId;
    $(document).on('click', '.delete-qr-btn', function() {
        deleteQrCodeId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        $.ajax({
            url: "{{ route('superadmin.payment.school-qr-codes.destroy', '') }}/" + deleteQrCodeId,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                table.ajax.reload();
                showAlert(response.message, 'success');
            },
            error: function() {
                showAlert('Something went wrong!', 'error');
            }
        });
    });

    // Load initial stats
    loadStats();
});

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : type === 'error' ? 'error-circle' : 'info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection
