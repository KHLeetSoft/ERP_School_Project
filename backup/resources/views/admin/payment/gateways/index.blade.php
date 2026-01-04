@extends('admin.layout.app')

@section('title', 'Payment Gateway Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Payment Gateway Settings</h4>
                    <a href="{{ route('admin.payment.gateways.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Add Gateway
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="gateways-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gateway Info</th>
                                    <th>Payment Methods</th>
                                    <th>Fees & Limits</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
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
    $('#gateways-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.payment.gateways.index') }}",
            type: 'GET'
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'gateway_info', name: 'gateway_info'},
            {data: 'payment_methods', name: 'payment_methods'},
            {data: 'fees', name: 'fees'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[4, 'desc']],
        pageLength: 25,
        responsive: true
    });

    // Toggle status
    $(document).on('click', '.toggle-status-btn', function() {
        var id = $(this).data('id');
        var button = $(this);
        
        if (confirm('Are you sure you want to toggle the status of this gateway?')) {
            $.ajax({
                url: "{{ url('admin/payment/gateways') }}/" + id + "/toggle-status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#gateways-table').DataTable().ajax.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        }
    });

    // Delete gateway
    $(document).on('click', '.delete-gateway-btn', function() {
        var id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this gateway? This action cannot be undone.')) {
            $.ajax({
                url: "{{ url('admin/payment/gateways') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Gateway deleted successfully!');
                    $('#gateways-table').DataTable().ajax.reload();
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
