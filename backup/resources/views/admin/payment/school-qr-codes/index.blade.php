@extends('admin.layout.app')

@section('title', 'School QR Code Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">School QR Code Management</h4>
            <p class="text-muted mb-0">Manage your school's payment QR codes (Multiple QR codes allowed)</p>
        </div>
        <div>
            <a href="{{ route('admin.payment.school-qr-codes.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Generate School QR Code
            </a>
        </div>
    </div>

    <!-- School QR Codes List -->
    <div class="row">
        <div class="col-lg-12">
            <!-- QR Codes Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">School QR Codes ({{ $school->name }})</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" id="refresh-table">
                                <i class="bx bx-refresh me-1"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="school-qr-codes-table">
                            <thead class="table-light">
                                <tr>
                                    <th>QR Code Info</th>
                                    <th>QR Code</th>
                                    <th>Amount</th>
                                    <th>Usage Stats</th>
                                    <th>Created</th>
                                    <th width="120">Actions</th>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#school-qr-codes-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.payment.school-qr-codes.index') }}",
        columns: [
            { data: 'qr_info', name: 'qr_info', orderable: false },
            { data: 'qr_code', name: 'qr_code', orderable: false },
            { data: 'amount_info', name: 'amount_info', orderable: false },
            { data: 'usage_stats', name: 'usage_stats', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[4, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: "Loading QR codes...",
            emptyTable: "No QR codes found",
            zeroRecords: "No matching QR codes found"
        }
    });

    // Refresh table
    $('#refresh-table').on('click', function() {
        table.ajax.reload();
    });

    // Toggle status
    $(document).on('click', '.toggle-status-btn', function() {
        var qrCodeId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: "{{ route('admin.payment.school-qr-codes.toggle-status', '') }}/" + qrCodeId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    table.ajax.reload();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error('Something went wrong!');
            }
        });
    });

    // Delete QR code
    var deleteQrCodeId;
    $(document).on('click', '.delete-qr-btn', function() {
        deleteQrCodeId = $(this).data('id');
        if (confirm('Are you sure you want to delete this QR code?')) {
            $.ajax({
                url: "{{ route('admin.payment.school-qr-codes.destroy', '') }}/" + deleteQrCodeId,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    table.ajax.reload();
                    toastr.success(response.message);
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        }
    });
});
</script>
@endpush

