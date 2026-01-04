@extends('admin.layout.app')

@section('title', 'Email Templates')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('styles')
<style>
.page-title-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.template-card {
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}
.template-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.template-card.inactive {
    border-left-color: #6c757d;
    opacity: 0.7;
}
.template-preview {
    font-size: 0.9rem;
    color: #6c757d;
}
.category-badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 0.5rem;
}
.status-indicator.active {
    background-color: #28a745;
}
.status-indicator.inactive {
    background-color: #6c757d;
}
.bulk-actions-bar {
    position: fixed;
    bottom: 0;
    left: 250px;
    right: 0;
    background: #fff;
    border-top: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
    display: none;
    z-index: 1050;
}
.bulk-actions-bar.show {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.filter-card {
    border-left: 4px solid #007bff;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-title-box">
        <h4 class="page-title">📧 Email Templates ({{ $templates->total() }})</h4>
        <a href="{{ route('admin.communications.email-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Template
        </a>
    </div>

    <!-- Filters -->
    <div class="filter-card mb-3">
        <button class="btn btn-link p-0 mb-2" type="button" data-toggle="collapse" data-target="#filtersCollapse">
            <i class="fas fa-filter"></i> Show/Hide Filters
        </button>
        <div class="collapse show" id="filtersCollapse">
            @include('admin.communications.email-templates.partials.filters')
        </div>
    </div>

    <!-- Templates List -->
    <div class="row">
        @forelse($templates as $template)
        <div class="col-12">
            <div class="template-card {{ $template->is_active ? '' : 'inactive' }} mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Checkbox -->
                            <div class="col-md-1">
                                <input type="checkbox" class="template-checkbox" value="{{ $template->id }}">
                            </div>
                            
                            <!-- Content -->
                            <div class="col-md-7">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 mr-2">{{ $template->name }}</h6>
                                    <span class="status-indicator {{ $template->is_active ? 'active' : 'inactive' }}"></span>
                                    <span class="badge {{ $template->category_badge }} category-badge ml-2">
                                        {{ ucfirst($template->category) }}
                                    </span>
                                </div>
                                
                                <div class="template-preview mb-2">
                                    <strong>Subject:</strong> {{ Str::limit($template->subject, 80) }}
                                </div>
                                
                                <div class="template-preview mb-2">
                                    <strong>Content:</strong> {{ Str::limit(strip_tags($template->content), 120) }}
                                </div>
                                
                                @if(!empty($template->variables))
                                <div class="template-preview">
                                    <strong>Variables:</strong> 
                                    <span class="text-info">{{ $template->variables_list }}</span>
                                </div>
                                @endif
                                
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> {{ $template->creator->name ?? 'Unknown' }} • 
                                    <i class="fas fa-calendar"></i> {{ $template->created_at->format('M d, Y H:i') }}
                                    @if($template->updated_at != $template->created_at)
                                        • <i class="fas fa-edit"></i> Updated {{ $template->updated_at->format('M d, Y H:i') }}
                                    @endif
                                </small>
                            </div>
                            
                            <!-- Actions -->
                            <div class="col-md-4 text-right">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.communications.email-templates.show', $template->id) }}" 
                                       class="btn btn-outline-primary" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="{{ route('admin.communications.email-templates.edit', $template->id) }}" 
                                       class="btn btn-outline-secondary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button class="btn btn-outline-info" 
                                            onclick="previewTemplate({{ $template->id }})" 
                                            title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    <button class="btn btn-outline-success" 
                                            onclick="toggleStatus({{ $template->id }})" 
                                            title="{{ $template->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $template->is_active ? 'pause' : 'play' }}"></i>
                                    </button>
                                    
                                    <button class="btn btn-outline-warning" 
                                            onclick="duplicateTemplate({{ $template->id }})" 
                                            title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    
                                    <button class="btn btn-outline-danger" 
                                            onclick="deleteTemplate({{ $template->id }})" 
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No Email Templates Found</h5>
                <p class="text-muted">Create your first email template to get started.</p>
                <a href="{{ route('admin.communications.email-templates.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Template
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $templates->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Bulk Actions Bar -->
<div class="bulk-actions-bar" id="bulkActionsBar">
    <div>
        <span id="selectedCount">0 templates selected</span>
    </div>
    <div>
        <button class="btn btn-success btn-sm mr-2" onclick="activateSelected()">
            <i class="fas fa-play"></i> Activate
        </button>
        <button class="btn btn-warning btn-sm mr-2" onclick="deactivateSelected()">
            <i class="fas fa-pause"></i> Deactivate
        </button>
        <button class="btn btn-danger btn-sm" onclick="deleteSelected()">
            <i class="fas fa-trash"></i> Delete
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Handle checkbox changes
    $('.template-checkbox').change(function() {
        updateBulkActionsBar();
    });

    // Select all checkbox
    $('#selectAll').change(function() {
        $('.template-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkActionsBar();
    });
});

function updateBulkActionsBar() {
    const selectedCount = $('.template-checkbox:checked').length;
    const bulkBar = $('#bulkActionsBar');
    
    if (selectedCount > 0) {
        $('#selectedCount').text(`${selectedCount} template${selectedCount > 1 ? 's' : ''} selected`);
        bulkBar.addClass('show');
    } else {
        bulkBar.removeClass('show');
    }
}

function previewTemplate(templateId) {
    $.ajax({
        url: `/admin/communications/email-templates/${templateId}/preview`,
        type: 'GET',
        success: function(response) {
            if (response.success) {
                showTemplatePreview(response.template);
            } else {
                toastr.error('Failed to load template preview');
            }
        },
        error: function() {
            toastr.error('Failed to load template preview');
        }
    });
}

function showTemplatePreview(template) {
    const modal = `
        <div class="modal fade" id="templatePreviewModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Template Preview: ${template.name}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Subject:</strong> ${template.subject}
                        </div>
                        <div class="mb-3">
                            <strong>Category:</strong> 
                            <span class="badge badge-info">${template.category}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Content:</strong>
                            <div class="border p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                                ${template.content}
                            </div>
                        </div>
                        ${template.variables && template.variables.length > 0 ? 
                            `<div class="mb-3">
                                <strong>Variables:</strong> 
                                <span class="text-info">${template.variables.join(', ')}</span>
                            </div>` : ''
                        }
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a href="/admin/communications/email-templates/${template.id}/edit" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Template
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    $('#templatePreviewModal').remove();
    
    // Add new modal to body
    $('body').append(modal);
    
    // Show modal
    $('#templatePreviewModal').modal('show');
}

function toggleStatus(templateId) {
    $.ajax({
        url: `/admin/communications/email-templates/${templateId}/toggle-status`,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                location.reload();
            } else {
                toastr.error(response.message || 'Failed to update template status');
            }
        },
        error: function() {
            toastr.error('Failed to update template status');
        }
    });
}

function duplicateTemplate(templateId) {
    if (confirm('Are you sure you want to duplicate this template?')) {
        $.ajax({
            url: `/admin/communications/email-templates/${templateId}/duplicate`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to duplicate template');
                }
            },
            error: function() {
                toastr.error('Failed to duplicate template');
            }
        });
    }
}

function deleteTemplate(templateId) {
    if (confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/communications/email-templates/${templateId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to delete template');
                }
            },
            error: function() {
                toastr.error('Failed to delete template');
            }
        });
    }
}

function activateSelected() {
    const selectedIds = $('.template-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('Please select templates to activate');
        return;
    }
    
    if (confirm(`Are you sure you want to activate ${selectedIds.length} selected templates?`)) {
        // Implement bulk activate functionality
        toastr.info('Bulk activate functionality will be implemented');
    }
}

function deactivateSelected() {
    const selectedIds = $('.template-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('Please select templates to deactivate');
        return;
    }
    
    if (confirm(`Are you sure you want to deactivate ${selectedIds.length} selected templates?`)) {
        // Implement bulk deactivate functionality
        toastr.info('Bulk deactivate functionality will be implemented');
    }
}

function deleteSelected() {
    const selectedIds = $('.template-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        toastr.warning('Please select templates to delete');
        return;
    }
    
    if (confirm(`Are you sure you want to delete ${selectedIds.length} selected templates? This action cannot be undone.`)) {
        // Implement bulk delete functionality
        toastr.info('Bulk delete functionality will be implemented');
    }
}
</script>
@endpush
