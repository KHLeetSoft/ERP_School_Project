@extends('admin.layout.app')

@section('title', 'Import Inventory Issues')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Import Inventory Issues</h1>
            <p class="text-muted">Import inventory issues from Excel/CSV file</p>
        </div>
        <a href="{{ route('admin.inventory.issues.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back"></i> Back to Issues
        </a>
    </div>

    <div class="row">
        <!-- Import Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Import File</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.inventory.issues.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="file" class="form-label">Select File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".xlsx,.xls,.csv" required>
                            <div class="form-text">Supported formats: Excel (.xlsx, .xls) and CSV files. Max size: 10MB</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1">
                                <label class="form-check-label" for="update_existing">
                                    Update existing issues (based on item, title, and date)
                                </label>
                                <div class="form-text">If checked, existing issues with matching item, title, and date will be updated.</div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.inventory.issues.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-upload"></i> Import Issues
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Help & Instructions -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Import Instructions</h6>
                </div>
                <div class="card-body">
                    <h6>Required Fields:</h6>
                    <ul class="small">
                        <li><strong>inventory_item_name</strong> - OR inventory_item_sku</li>
                        <li><strong>issue_type</strong> - damaged, lost, stolen, maintenance, other</li>
                        <li><strong>title</strong> - Issue title</li>
                    </ul>

                    <h6 class="mt-3">Optional Fields:</h6>
                    <ul class="small">
                        <li><strong>description</strong> - Issue description</li>
                        <li><strong>priority</strong> - low, medium, high, critical</li>
                        <li><strong>status</strong> - open, in_progress, resolved, closed</li>
                        <li><strong>quantity_affected</strong> - Number of items affected</li>
                        <li><strong>estimated_cost</strong> - Estimated repair cost</li>
                        <li><strong>issue_date</strong> - Date issue occurred (YYYY-MM-DD)</li>
                        <li><strong>resolved_date</strong> - Date issue was resolved (YYYY-MM-DD)</li>
                        <li><strong>reported_by</strong> - Person who reported the issue</li>
                        <li><strong>assigned_to</strong> - Person assigned to fix the issue</li>
                        <li><strong>location</strong> - Where the issue occurred</li>
                        <li><strong>resolution_notes</strong> - Notes about resolution</li>
                        <li><strong>is_active</strong> - Active status (1 or 0)</li>
                    </ul>

                    <h6 class="mt-3">Valid Values:</h6>
                    <ul class="small">
                        <li><strong>Issue Types:</strong> damaged, lost, stolen, maintenance, other</li>
                        <li><strong>Priorities:</strong> low, medium, high, critical</li>
                        <li><strong>Statuses:</strong> open, in_progress, resolved, closed</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sample File</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Download a sample Excel file to see the correct format and structure.</p>
                    <a href="{{ route('admin.inventory.issues.sample') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-download"></i> Download Sample
                    </a>
                </div>
            </div>

            <div class="card shadow mt-3">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Available Items</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Use these item names or SKUs in your import file:</p>
                    <div style="max-height: 200px; overflow-y: auto;">
                        @foreach($inventoryItems as $item)
                            <div class="small mb-1">
                                <strong>{{ $item->name }}</strong><br>
                                <code>{{ $item->sku }}</code>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Issues -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Common Import Issues</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Validation Errors:</h6>
                            <ul class="small">
                                <li>Inventory item not found</li>
                                <li>Invalid issue type/priority/status</li>
                                <li>Invalid date formats</li>
                                <li>Missing required fields</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Solutions:</h6>
                            <ul class="small">
                                <li>Use exact item names or SKUs</li>
                                <li>Use valid enum values</li>
                                <li>Use YYYY-MM-DD date format</li>
                                <li>Fill all required fields</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
