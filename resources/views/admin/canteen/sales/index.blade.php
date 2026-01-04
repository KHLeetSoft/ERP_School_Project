@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">
                <i class="fas fa-receipt me-2 text-primary"></i> Canteen Sales
            </h4>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.canteen.sales.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add Sale
                </a>
                <a href="{{ route('admin.canteen.sales.export', request()->query()) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-file-export"></i> Export CSV
                </a>
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-file-import"></i> Import CSV
                </button>
                <a href="{{ route('admin.canteen.sales.sample') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-download"></i> Sample CSV
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form class="row g-2 mb-3" method="GET" action="{{ route('admin.canteen.sales.index') }}">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control" placeholder="Search item name" value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <select name="item_id" class="form-select">
                        <option value="">All Items</option>
                        @foreach($items as $it)
                            <option value="{{ $it->id }}" {{ request('item_id')==$it->id ? 'selected' : '' }}>{{ $it->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-secondary" type="submit">Filter</button>
                    <a class="btn btn-light" href="{{ route('admin.canteen.sales.index') }}">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <form method="POST" action="{{ route('admin.canteen.sales.bulk-destroy') }}" id="bulkForm">
                    @csrf
                    <table class="table table-striped mb-0" id="salesTable">
                        <thead class="table-dark">
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>#</th>
                                <th>Sold At</th>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                <th>Buyer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-sm btn-outline-danger" form="bulkForm" onclick="return confirm('Delete selected sales?')">Bulk Delete</button>
        </div>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel"><i class="fas fa-file-import me-2"></i> Import Sales</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.canteen.sales.import') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Upload CSV file</label>
                            <input type="file" name="file" class="form-control" accept=".csv,text/csv" required>
                            <div class="form-text">Columns: canteen_item_id, quantity, unit_price, sold_at, buyer_type, buyer_id, notes</div>
                        </div>
                        <a href="{{ route('admin.canteen.sales.sample') }}" class="small">Download sample CSV</a>
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
@endsection

@section('scripts')
<script>
$(function() {
    const table = $('#salesTable').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        lengthChange: true,
        pageLength: 10,
        ajax: {
            url: "{{ route('admin.canteen.sales.index') }}",
            data: function(d) {
                d.q = $('input[name="q"]').val();
                d.item_id = $('select[name="item_id"]').val();
                d.date_from = $('input[name="date_from"]').val();
                d.date_to = $('input[name="date_to"]').val();
            }
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'id' },
            { data: 'sold_at' },
            { data: 'item' },
            { data: 'quantity' },
            { data: 'unit_price' },
            { data: 'total_amount' },
            { data: 'buyer' },
            { data: 'action', orderable: false, searchable: false },
        ],
        order: [[2, 'desc']]
    });

    $('#checkAll').on('change', function() {
        $('.row-check').prop('checked', this.checked);
    });

    $('input[name="q"], select[name="item_id"], input[name="date_from"], input[name="date_to"]').on('change keyup', function() {
        table.draw();
    });
});
</script>
@endsection


