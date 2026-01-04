@extends('superadmin.app')

@section('title', 'QR Code Limits Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('superadmin.payment.school-qr-codes.index') }}">QR Codes</a></li>
                        <li class="breadcrumb-item active">Limits Management</li>
                    </ol>
                </div>
                <h4 class="page-title">QR Code Limits Management</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-qrcode widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total Schools">Total Schools</h5>
                    <h3 class="mt-3 mb-3" id="total-schools">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-check-circle widget-icon text-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Schools with Payment">Paid Schools</h5>
                    <h3 class="mt-3 mb-3" id="paid-schools">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-alert-circle widget-icon text-warning"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Schools Requiring Payment">Payment Required</h5>
                    <h3 class="mt-3 mb-3" id="payment-required">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-qrcode-edit widget-icon text-info"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0" title="Total QR Codes Generated">Total QR Codes</h5>
                    <h3 class="mt-3 mb-3" id="total-qr-codes">-</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="header-title">School QR Code Limits</h4>
                            <p class="text-muted mb-0">Manage QR code generation limits for each school</p>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-input" placeholder="Search schools...">
                                <button class="btn btn-outline-secondary" type="button" id="search-btn">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="limits-datatable" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>School Name</th>
                                    <th>Admin</th>
                                    <th>QR Codes Generated</th>
                                    <th>Current Limit</th>
                                    <th>Remaining</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Limit Modal -->
<div class="modal fade" id="editLimitModal" tabindex="-1" aria-labelledby="editLimitModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLimitModalLabel">Edit QR Code Limit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLimitForm">
                <div class="modal-body">
                    <input type="hidden" id="school_id" name="school_id">
                    <div class="mb-3">
                        <label for="school_name" class="form-label">School Name</label>
                        <input type="text" class="form-control" id="school_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="current_limit" class="form-label">Current Limit</label>
                        <input type="text" class="form-control" id="current_limit" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="qr_codes_generated" class="form-label">QR Codes Generated</label>
                        <input type="text" class="form-control" id="qr_codes_generated" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="qr_code_limit" class="form-label">New Limit <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="qr_code_limit" name="qr_code_limit" min="1" max="100" required>
                        <div class="form-text">Maximum 100 QR codes per school</div>
                    </div>
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Change</label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Optional reason for changing the limit"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Limit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#limits-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('superadmin.payment.school-qr-codes.limits') }}",
            data: function(d) {
                d.search = $('#search-input').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'admin_name', name: 'admin_name'},
            {data: 'qr_codes_count', name: 'qr_codes_count'},
            {data: 'qr_code_limit', name: 'qr_code_limit'},
            {data: 'remaining_qr_codes', name: 'remaining_qr_codes'},
            {data: 'payment_status', name: 'payment_status', orderable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading data...",
            emptyTable: "No schools found"
        }
    });

    // Search functionality
    $('#search-btn').click(function() {
        table.draw();
    });

    $('#search-input').keypress(function(e) {
        if (e.which == 13) {
            table.draw();
        }
    });

    // Load statistics
    loadStatistics();

    // Edit limit modal
    $(document).on('click', '.edit-limit-btn', function() {
        var schoolId = $(this).data('school-id');
        var schoolName = $(this).data('school-name');
        var currentLimit = $(this).data('current-limit');
        var qrCodesGenerated = $(this).data('qr-codes-generated');
        
        $('#school_id').val(schoolId);
        $('#school_name').val(schoolName);
        $('#current_limit').val(currentLimit);
        $('#qr_codes_generated').val(qrCodesGenerated);
        $('#qr_code_limit').val(currentLimit);
        
        $('#editLimitModal').modal('show');
    });

    // Update limit form
    $('#editLimitForm').submit(function(e) {
        e.preventDefault();
        
        var schoolId = $('#school_id').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "{{ route('superadmin.payment.school-qr-codes.update-limit', ':school') }}".replace(':school', schoolId),
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editLimitModal').modal('hide');
                    table.draw();
                    loadStatistics();
                    toastr.success(response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function(key, value) {
                        toastr.error(value[0]);
                    });
                }
            }
        });
    });
});

function loadStatistics() {
    $.ajax({
        url: "{{ route('superadmin.payment.school-qr-codes.limits') }}",
        type: 'GET',
        data: { statistics: true },
        success: function(response) {
            $('#total-schools').text(response.total_schools || 0);
            $('#paid-schools').text(response.paid_schools || 0);
            $('#payment-required').text(response.payment_required || 0);
            $('#total-qr-codes').text(response.total_qr_codes || 0);
        }
    });
}
</script>
@endpush
