@extends('admin.layout.app')

@section('title', 'Inventory Categories')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tags me-2"></i>Inventory Categories
            </h1>
            <p class="text-muted mb-0">Manage inventory item categories</p>
        </div>
        <a href="{{ route('admin.inventory.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Category
        </a>
    </div>

    <!-- DataTable Card -->
    <div class="card shadow">
        <div class="card-header">
            <h5 class="card-title mb-0">Categories List</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="categoriesTable" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Items</th>
                            <th>Sort Order</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.category-icon {
    width: 30px;
    height: 30px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    margin-right: 8px;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}

.table th {
    background-color: #f8f9fc;
    border-top: none;
    font-weight: 600;
    color: #5a5c69;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin-right: 2px;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_processing,
.dataTables_wrapper .dataTables_paginate {
    margin-bottom: 1rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.375rem 0.75rem;
    margin-left: 2px;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #007bff;
    color: white !important;
    border-color: #007bff;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#categoriesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.inventory.categories.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'category', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'items_count', name: 'items_count'},
            {data: 'sort_order', name: 'sort_order'},
            {data: 'status', name: 'is_active'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        order: [[4, 'asc'], [1, 'asc']], // Sort by sort_order, then name
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        language: {
            processing: "Loading categories...",
            emptyTable: "No categories found",
            zeroRecords: "No matching categories found"
        }
    });

    // Toggle status functionality
    $(document).on('click', '.toggle-status-btn', function() {
        var categoryId = $(this).data('id');
        var button = $(this);
        
        if (confirm('Are you sure you want to toggle the status of this category?')) {
            $.ajax({
                url: '/admin/inventory/categories/' + categoryId + '/toggle-status',
                type: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#categoriesTable').DataTable().ajax.reload();
                    toastr.success('Category status updated successfully!');
                },
                error: function(xhr) {
                    toastr.error('Error updating category status!');
                }
            });
        }
    });

    // Delete category functionality
    $(document).on('click', '.delete-category-btn', function() {
        var categoryId = $(this).data('id');
        var button = $(this);
        
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            $.ajax({
                url: '/admin/inventory/categories/' + categoryId,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#categoriesTable').DataTable().ajax.reload();
                    toastr.success('Category deleted successfully!');
                },
                error: function(xhr) {
                    toastr.error('Error deleting category!');
                }
            });
        }
    });
});
</script>
@endsection
