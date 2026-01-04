@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-utensils me-2 text-primary"></i> Canteen Items
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.canteen.items.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Item
                </a>
                <a href="{{ route('admin.canteen.items.export', request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export"></i> Export CSV
                </a>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import"></i> Import CSV
                </button>
                <a href="{{ route('admin.canteen.items.sample') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-download"></i> Sample CSV
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET" action="{{ route('admin.canteen.items.index') }}">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control" placeholder="Search name/description" value="{{ request('q') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status')==='inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-secondary" type="submit">Filter</button>
                    <a class="btn btn-light" href="{{ route('admin.canteen.items.index') }}">Reset</a>
                </div>
            </form>

            <!-- Import Modal -->
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel"><i class="fas fa-file-import me-2"></i> Import Canteen Items</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.canteen.items.import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Upload CSV file</label>
                                    <input type="file" name="file" class="form-control" accept=".csv,text/csv" required>
                                    <div class="form-text">Columns: name, price, stock_quantity, is_active, description</div>
                                </div>
                                <a href="{{ route('admin.canteen.items.sample') }}" class="small">Download sample CSV</a>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-warning"><i class="fas fa-file-import"></i> Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <form method="POST" action="{{ route('admin.canteen.items.bulk-destroy') }}" id="bulkForm">
                    @csrf
                    <table class="table table-striped mb-0" id="itemsTable">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-outline-danger" form="bulkForm" onclick="return confirm('Delete selected items?')">Bulk Delete</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    const table = $('#itemsTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        lengthChange: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('admin.canteen.items.index') }}",
            data: function(d) {
                d.q = $('input[name="q"]').val();
                d.status = $('select[name="status"]').val();
                d.min_price = $('input[name="min_price"]').val();
                d.max_price = $('input[name="max_price"]').val();
            }
        },
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Left side: Show
                '<"col-md-6 text-end"f>' +    // Right side: Search
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      // Next line: Buttons full width right aligned
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' },
        ],
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'id' },
            { data: 'name' },
            { data: 'price' },
            { data: 'stock_quantity' },
            { data: 'status', orderable: false, searchable: false },
            { data: 'action', orderable: false, searchable: false },
        ],
        order: [[1, 'desc']]
    });

    $('#checkAll').on('change', function() {
        $('.row-check').prop('checked', this.checked);
    });

    // Re-draw when filters change
    $('input[name="q"], select[name="status"], input[name="min_price"], input[name="max_price"]').on('change keyup', function() {
        table.draw();
    });
});
</script>
@endsection


