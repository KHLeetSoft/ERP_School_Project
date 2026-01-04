@extends('admin.layout.app')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Stock Management</h1>
        <div>
            <a href="{{ route('admin.inventory.stock.movements') }}" class="btn btn-info btn-sm">
                <i class="fas fa-history"></i> All Movements
            </a>
            <a href="{{ route('admin.inventory.stock.statistics') }}" class="btn btn-primary btn-sm" id="statisticsBtn">
                <i class="fas fa-chart-bar"></i> Statistics
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_items'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Low Stock Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock_items'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Out of Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['out_of_stock_items'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($stats['total_value'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.inventory.stock.index') }}" class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search by name, SKU, or category">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="stock_status" class="form-label">Stock Status</label>
                    <select class="form-control" id="stock_status" name="stock_status">
                        <option value="">All Items</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        <option value="overstocked" {{ request('stock_status') == 'overstocked' ? 'selected' : '' }}>Overstocked</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.inventory.stock.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Inventory Items Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inventory Items</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="inventoryTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Min Quantity</th>
                            <th>Status</th>
                            <th>Value</th>
                            <th>Last Movement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventoryItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" 
                                                 class="img-thumbnail me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-box text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-weight-bold">{{ $item->name }}</div>
                                            <small class="text-muted">SKU: {{ $item->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->category }}</td>
                                <td>
                                    <span class="font-weight-bold">{{ $item->quantity }}</span>
                                    <small class="text-muted">{{ $item->unit }}</small>
                                </td>
                                <td>{{ $item->min_quantity }} {{ $item->unit }}</td>
                                <td>
                                    @if($item->quantity == 0)
                                        <span class="badge badge-danger">Out of Stock</span>
                                    @elseif($item->quantity <= $item->min_quantity)
                                        <span class="badge badge-warning">Low Stock</span>
                                    @elseif($item->quantity > ($item->min_quantity * 3))
                                        <span class="badge badge-info">Overstocked</span>
                                    @else
                                        <span class="badge badge-success">In Stock</span>
                                    @endif
                                </td>
                                <td>₹{{ number_format($item->quantity * $item->price, 2) }}</td>
                                <td>
                                    @if($item->stockMovements->count() > 0)
                                        <div class="small">
                                            <div>{{ $item->stockMovements->first()->formatted_movement_type }}</div>
                                            <div class="text-muted">{{ $item->stockMovements->first()->movement_date->format('M d, Y') }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted">No movements</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.inventory.stock.adjust', $item) }}" 
                                           class="btn btn-primary btn-sm" title="Adjust Stock">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.inventory.stock.history', $item) }}" 
                                           class="btn btn-info btn-sm" title="View History">
                                            <i class="fas fa-history"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No inventory items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $inventoryItems->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

    <!-- Recent Movements -->
    @if($stats['recent_movements']->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recent Stock Movements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Performed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recent_movements'] as $movement)
                            <tr>
                                <td>{{ $movement->movement_date->format('M d, Y') }}</td>
                                <td>{{ $movement->inventoryItem->name }}</td>
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
                                <td>{{ $movement->performed_by }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statisticsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Statistics</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="statisticsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
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
    // Statistics button click
    $('#statisticsBtn').click(function(e) {
        e.preventDefault();
        $('#statisticsModal').modal('show');
        
        // Load statistics via AJAX
        $.get('{{ route("admin.inventory.stock.statistics") }}', function(data) {
            let content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Statistics</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Items:</span>
                                <strong>${data.total_items}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Low Stock Items:</span>
                                <strong class="text-warning">${data.low_stock_items}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Out of Stock Items:</span>
                                <strong class="text-danger">${data.out_of_stock_items}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Total Value:</span>
                                <strong>₹${parseFloat(data.total_value).toLocaleString()}</strong>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Movement Statistics</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Movements Today:</span>
                                <strong>${data.movements_today}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Movements This Month:</span>
                                <strong>${data.movements_this_month}</strong>
                            </li>
                        </ul>
                        
                        <h6 class="mt-3">Movements by Type</h6>
                        <ul class="list-group list-group-flush">
            `;
            
            Object.keys(data.movements_by_type).forEach(type => {
                content += `
                    <li class="list-group-item d-flex justify-content-between">
                        <span>${type.replace('_', ' ').toUpperCase()}:</span>
                        <strong>${data.movements_by_type[type]}</strong>
                    </li>
                `;
            });
            
            content += `
                        </ul>
                    </div>
                </div>
            `;
            
            $('#statisticsContent').html(content);
        });
    });
});
</script>
@endpush
