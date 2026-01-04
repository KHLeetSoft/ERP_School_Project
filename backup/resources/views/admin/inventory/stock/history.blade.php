@extends('admin.layout.app')

@section('title', 'Stock History - ' . $item->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stock History</h1>
        <div>
            <a href="{{ route('admin.inventory.stock.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Stock
            </a>
            <a href="{{ route('admin.inventory.stock.adjust', $item) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Adjust Stock
            </a>
        </div>
    </div>

    <!-- Item Information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Item Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" 
                             class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-box fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $item->name }}</h5>
                            <p class="text-muted mb-1"><strong>SKU:</strong> {{ $item->sku }}</p>
                            <p class="text-muted mb-1"><strong>Category:</strong> {{ $item->category }}</p>
                            <p class="text-muted mb-1"><strong>Unit:</strong> {{ $item->unit }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-primary mb-0">{{ $item->quantity }}</h4>
                                        <small class="text-muted">Current Stock</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-warning mb-0">{{ $item->min_quantity }}</h4>
                                        <small class="text-muted">Min Quantity</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.inventory.stock.history', $item) }}" class="row">
                <div class="col-md-4 mb-3">
                    <label for="movement_type" class="form-label">Movement Type</label>
                    <select class="form-control" id="movement_type" name="movement_type">
                        <option value="">All Types</option>
                        @foreach($movementTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('movement_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ request('end_date') }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.inventory.stock.history', $item) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Stock Movements Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Stock Movements</h6>
        </div>
        <div class="card-body">
            @if($movements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="movementsTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Previous</th>
                                <th>New</th>
                                <th>Reference</th>
                                <th>Performed By</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                <tr>
                                    <td>
                                        <div class="small">
                                            <div class="font-weight-bold">{{ $movement->movement_date->format('M d, Y') }}</div>
                                            <div class="text-muted">{{ $movement->movement_date->format('h:i A') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $movement->is_positive ? 'success' : 'danger' }}">
                                            {{ $movement->formatted_movement_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold {{ $movement->is_positive ? 'text-success' : 'text-danger' }}">
                                            {{ $movement->quantity_change }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->previous_quantity }} {{ $item->unit }}</td>
                                    <td>
                                        <span class="font-weight-bold">{{ $movement->new_quantity }} {{ $item->unit }}</span>
                                    </td>
                                    <td>
                                        @if($movement->reference_number)
                                            <div class="small">
                                                <div><strong>{{ $movement->reference_type ?? 'Manual' }}</strong></div>
                                                <div class="text-muted">{{ $movement->reference_number }}</div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $movement->performed_by }}</td>
                                    <td>
                                        @if($movement->notes)
                                            <span class="text-truncate d-inline-block" style="max-width: 150px;" 
                                                  title="{{ $movement->notes }}">
                                                {{ $movement->notes }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $movements->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No stock movements found</h5>
                    <p class="text-muted">This item has no stock movement history yet.</p>
                    <a href="{{ route('admin.inventory.stock.adjust', $item) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Make First Adjustment
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Statistics -->
    @if($movements->count() > 0)
    <div class="row">
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total In</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $movements->whereIn('movement_type', ['in', 'return'])->sum('quantity') }} {{ $item->unit }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-up fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Out</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $movements->whereIn('movement_type', ['out', 'damage', 'loss', 'transfer'])->sum('quantity') }} {{ $item->unit }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Movements</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $movements->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable if there are movements
    @if($movements->count() > 0)
    $('#movementsTable').DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 25,
        "responsive": true,
        "columnDefs": [
            { "orderable": false, "targets": [7] } // Notes column
        ]
    });
    @endif
});
</script>
@endpush
