@extends('accountant.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bx bx-check-circle me-2"></i>Payment Verification
                    </h1>
                    <p class="text-muted mb-0">Verify and record payment transactions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Total Transactions</h5>
                            <h2 class="mb-0 text-white" id="totalTransactions">0</h2>
                        </div>
                        <i class="bx bx-credit-card display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Successful</h5>
                            <h2 class="mb-0 text-white" id="successfulTransactions">0</h2>
                        </div>
                        <i class="bx bx-check-circle display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Pending</h5>
                            <h2 class="mb-0 text-white" id="pendingTransactions">0</h2>
                        </div>
                        <i class="bx bx-time display-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-white mb-0">Failed</h5>
                            <h2 class="mb-0 text-white" id="failedTransactions">0</h2>
                        </div>
                        <i class="bx bx-x-circle display-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-3">
            <select class="form-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="success">Success</option>
                <option value="failed">Failed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" id="dateFromFilter" placeholder="From Date">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control" id="dateToFilter" placeholder="To Date">
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary" onclick="refreshTable()">
                <i class="bx bx-refresh me-2"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="transactions-table">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span class="fw-semibold text-dark">#</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-credit-card me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Transaction Info</span>
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
                                    <i class="bx bx-credit-card me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Gateway</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-user me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">User</span>
                                </div>
                            </th>
                            <th class="border-0 py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-file me-2 text-primary"></i>
                                    <span class="fw-semibold text-dark">Invoice</span>
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

<!-- Verification Modal -->
<div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="verificationModalLabel">
                    <i class="bx bx-check me-2"></i>Verify Transaction
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="verificationForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        <strong>Note:</strong> This will mark the transaction as verified and processed.
                    </div>
                    
                    <div class="mb-3">
                        <label for="verification_notes" class="form-label fw-bold">Verification Notes</label>
                        <textarea class="form-control" id="verification_notes" name="verification_notes" 
                                  rows="3" placeholder="Add any notes about this verification..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check me-2"></i>Verify Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Retry Modal -->
<div class="modal fade" id="retryModal" tabindex="-1" aria-labelledby="retryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="retryModalLabel">
                    <i class="bx bx-refresh me-2"></i>Retry Payment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="retryForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bx bx-error-circle me-2"></i>
                        <strong>Warning:</strong> This will attempt to retry the failed payment.
                    </div>
                    
                    <div class="mb-3">
                        <label for="retry_notes" class="form-label fw-bold">Retry Notes</label>
                        <textarea class="form-control" id="retry_notes" name="retry_notes" 
                                  rows="3" placeholder="Add any notes about this retry attempt..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bx bx-refresh me-2"></i>Retry Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#transactions-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('accountant.payment.verification.index') }}",
            data: function(d) {
                d.status = $('#statusFilter').val();
                d.date_from = $('#dateFromFilter').val();
                d.date_to = $('#dateToFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'transaction_info', name: 'transaction_id', orderable: false },
            { data: 'amount_info', name: 'amount', orderable: false },
            { data: 'gateway_info', name: 'gateway.name', orderable: false },
            { data: 'user_info', name: 'user.name', orderable: false },
            { data: 'invoice_status', name: 'invoice_id', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[6, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
        }
    });

    // Load statistics
    loadStats();

    // Filter change events
    $('#statusFilter, #dateFromFilter, #dateToFilter').on('change', function() {
        table.draw();
        loadStats();
    });

    // Verify transaction
    var verifyTransactionId;
    $(document).on('click', '.verify-btn', function() {
        verifyTransactionId = $(this).data('id');
        $('#verificationModal').modal('show');
    });

    $('#verificationForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('accountant.payment.verification.verify', '') }}/" + verifyTransactionId,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#verificationModal').modal('hide');
                    table.draw();
                    loadStats();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('An error occurred while verifying transaction', 'error');
            }
        });
    });

    // Retry payment
    var retryTransactionId;
    $(document).on('click', '.retry-btn', function() {
        retryTransactionId = $(this).data('id');
        $('#retryModal').modal('show');
    });

    $('#retryForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('accountant.payment.verification.retry', '') }}/" + retryTransactionId,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#retryModal').modal('hide');
                    table.draw();
                    loadStats();
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function() {
                showAlert('An error occurred while retrying payment', 'error');
            }
        });
    });
});

function loadStats() {
    $.ajax({
        url: "{{ route('accountant.payment.verification.index') }}",
        data: {
            stats_only: true,
            status: $('#statusFilter').val(),
            date_from: $('#dateFromFilter').val(),
            date_to: $('#dateToFilter').val()
        },
        success: function(response) {
            $('#totalTransactions').text(response.total || 0);
            $('#successfulTransactions').text(response.successful || 0);
            $('#pendingTransactions').text(response.pending || 0);
            $('#failedTransactions').text(response.failed || 0);
        }
    });
}

function refreshTable() {
    $('#transactions-table').DataTable().ajax.reload();
    loadStats();
}

function showAlert(message, type) {
    var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    var alertHtml = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
    
    $('.container-fluid').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection
