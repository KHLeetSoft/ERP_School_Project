@extends('admin.layout.app')

@section('title', 'Email Template Details')

@section('styles')
<style>
.template-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}
.template-content {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.info-card {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.variable-badge {
    background: #e3f2fd;
    color: #1976d2;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.8rem;
    margin: 0.25rem;
    display: inline-block;
}
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.9rem;
}
.status-badge.active {
    background: #d4edda;
    color: #155724;
}
.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}
.content-preview {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1.5rem;
    max-height: 500px;
    overflow-y: auto;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.email-templates.index') }}">Email Templates</a></li>
                        <li class="breadcrumb-item active">{{ $template->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">Email Template Details</h4>
            </div>
        </div>
    </div>

    <!-- Template Header -->
    <div class="template-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">{{ $template->name }}</h2>
                <p class="mb-0 opacity-75">{{ $template->subject }}</p>
            </div>
            <div class="col-md-4 text-right">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.communications.email-templates.edit', $template->id) }}" 
                       class="btn btn-light btn-lg mr-2">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('admin.communications.email-templates.index') }}" 
                       class="btn btn-outline-light btn-lg">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Template Information -->
        <div class="col-md-4">
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Template Information
                </h6>
                
                <div class="mb-3">
                    <strong>Status:</strong>
                    <span class="status-badge {{ $template->is_active ? 'active' : 'inactive' }} ml-2">
                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="mb-3">
                    <strong>Category:</strong>
                    <span class="badge badge-info ml-2">{{ ucfirst($template->category) }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Created By:</strong>
                    <div class="text-muted">{{ $template->creator->name ?? 'Unknown' }}</div>
                </div>
                
                <div class="mb-3">
                    <strong>Created:</strong>
                    <div class="text-muted">{{ $template->created_at->format('M d, Y H:i') }}</div>
                </div>
                
                @if($template->updated_at != $template->created_at)
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <div class="text-muted">{{ $template->updated_at->format('M d, Y H:i') }}</div>
                </div>
                @endif
                
                @if($template->updater && $template->updater->id != $template->creator->id)
                <div class="mb-3">
                    <strong>Updated By:</strong>
                    <div class="text-muted">{{ $template->updater->name }}</div>
                </div>
                @endif
            </div>

            <!-- Variables -->
            @if(!empty($template->variables))
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-code mr-2"></i>Template Variables
                </h6>
                <div>
                    @foreach($template->variablesWithSyntax as $variable)
                        <span class="variable-badge">{{ $variable }}</span>
                    @endforeach
                </div>
                <small class="text-muted mt-2 d-block">
                    These variables will be automatically replaced when sending emails.
                </small>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="info-card">
                <h6 class="mb-3">
                    <i class="fas fa-bolt mr-2"></i>Quick Actions
                </h6>
                
                <div class="d-grid gap-2">
                    @if($template->is_active)
                    <button class="btn btn-warning btn-sm" onclick="toggleStatus({{ $template->id }})">
                        <i class="fas fa-pause"></i> Deactivate
                    </button>
                    @else
                    <button class="btn btn-success btn-sm" onclick="toggleStatus({{ $template->id }})">
                        <i class="fas fa-play"></i> Activate
                    </button>
                    @endif
                    
                    <button class="btn btn-info btn-sm" onclick="duplicateTemplate({{ $template->id }})">
                        <i class="fas fa-copy"></i> Duplicate
                    </button>
                    
                    <button class="btn btn-danger btn-sm" onclick="deleteTemplate({{ $template->id }})">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Template Content -->
        <div class="col-md-8">
            <div class="template-content">
                <h6 class="mb-3">
                    <i class="fas fa-envelope mr-2"></i>Email Content
                </h6>
                
                <div class="content-preview">
                    {!! $template->content !!}
                </div>
                
                <div class="mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle mr-1"></i>
                        This is how the email will appear to recipients. Variables will be replaced with actual data.
                    </small>
                </div>
            </div>

            <!-- Usage Examples -->
            <div class="template-content mt-4">
                <h6 class="mb-3">
                    <i class="fas fa-lightbulb mr-2"></i>Usage Examples
                </h6>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">Student Welcome Email</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">Use variables like:</p>
                                <ul class="mb-0">
                                    <li><code>{{student_name}}</code> - Student's full name</li>
                                    <li><code>{{student_class}}</code> - Student's class</li>
                                    <li><code>{{school_name}}</code> - School name</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Parent Notification</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-2">Use variables like:</p>
                                <ul class="mb-0">
                                    <li><code>{{parent_name}}</code> - Parent's name</li>
                                    <li><code>{{student_name}}</code> - Student's name</li>
                                    <li><code>{{current_date}}</code> - Current date</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
                    window.location.href = '{{ route("admin.communications.email-templates.index") }}';
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
                    window.location.href = '{{ route("admin.communications.email-templates.index") }}';
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
</script>
@endsection
