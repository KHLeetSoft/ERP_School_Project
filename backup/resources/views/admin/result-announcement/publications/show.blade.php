@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bx bx-file-pdf me-2 text-primary"></i>Result Publication Details</h4>
        <div>
            <a href="{{ route('admin.result-announcement.publications.edit', $publication) }}" 
               class="btn btn-primary btn-sm me-2">
                <i class="bx bx-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.result-announcement.publications.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Publication Details -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $publication->publication_title }}</h5>
                </div>
                <div class="card-body">
                    @if($publication->publication_content)
                        <div class="mb-4">
                            <h6>Content</h6>
                            <p class="text-muted">{{ $publication->publication_content }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Type:</strong>
                                <span class="badge bg-info ms-2">{{ $publication->publication_type_text }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($publication->status === 'published')
                                    <span class="badge bg-success ms-2">Published</span>
                                @elseif($publication->status === 'draft')
                                    <span class="badge bg-secondary ms-2">Draft</span>
                                @else
                                    <span class="badge bg-warning ms-2">Archived</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Created By:</strong>
                                <span class="ms-2">{{ $publication->creator->name ?? 'N/A' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Created:</strong>
                                <span class="ms-2">{{ $publication->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Announcement:</strong>
                                <span class="ms-2">{{ $publication->resultAnnouncement->title ?? 'N/A' }}</span>
                            </div>

                            @if($publication->published_at)
                                <div class="mb-3">
                                    <strong>Published Date:</strong>
                                    <span class="ms-2">{{ $publication->published_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif

                            @if($publication->expires_at)
                                <div class="mb-3">
                                    <strong>Expiry Date:</strong>
                                    <span class="ms-2">{{ $publication->expires_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Featured:</strong>
                                @if($publication->is_featured)
                                    <span class="badge bg-primary ms-2">Featured</span>
                                @else
                                    <span class="badge bg-light text-dark ms-2">Regular</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Settings -->
            @if($publication->template_settings)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Template Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if(isset($publication->template_settings['theme']))
                                <div class="col-md-6">
                                    <strong>Theme:</strong>
                                    <span class="ms-2">{{ ucfirst($publication->template_settings['theme']) }}</span>
                                </div>
                            @endif
                            @if(isset($publication->template_settings['primary_color']))
                                <div class="col-md-6">
                                    <strong>Primary Color:</strong>
                                    <span class="ms-2">
                                        <div class="d-inline-block" style="width: 20px; height: 20px; background-color: {{ $publication->template_settings['primary_color'] }}; border: 1px solid #ddd;"></div>
                                        {{ $publication->template_settings['primary_color'] }}
                                    </span>
                                </div>
                            @endif
                            @if(isset($publication->template_settings['include_logo']))
                                <div class="col-md-6">
                                    <strong>Include Logo:</strong>
                                    <span class="ms-2">{{ $publication->template_settings['include_logo'] ? 'Yes' : 'No' }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Access Permissions -->
            @if($publication->access_permissions)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Access Permissions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($publication->access_permissions as $permission)
                                <div class="col-md-3">
                                    <span class="badge bg-success me-1">{{ ucfirst($permission) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($publication->status === 'draft')
                        <form method="POST" action="{{ route('admin.result-announcement.publications.publish', $publication) }}" 
                              class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bx bx-publish me-1"></i> Publish Now
                            </button>
                        </form>
                    @endif

                    @if($publication->status === 'published')
                        <form method="POST" action="{{ route('admin.result-announcement.publications.archive', $publication) }}" 
                              class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bx bx-archive me-1"></i> Archive
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.result-announcement.publications.send-notifications', $publication) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-send me-1"></i> Send Notifications (Students & Parents)
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.result-announcement.publications.toggle-featured', $publication) }}" 
                          class="mb-2">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-info w-100">
                            <i class="bx {{ $publication->is_featured ? 'bx-star' : 'bx-star' }} me-1"></i>
                            {{ $publication->is_featured ? 'Unfeature' : 'Feature' }}
                        </button>
                    </form>

                    @if(!$publication->pdf_file_path)
                        <form method="POST" action="{{ route('admin.result-announcement.publications.generate-pdf', $publication) }}" 
                              class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="bx bx-file-pdf me-1"></i> Generate PDF
                            </button>
                        </form>
                    @endif

                    @if($publication->pdf_file_path)
                        <a href="{{ route('admin.result-announcement.publications.download', $publication) }}" 
                           class="btn btn-outline-primary w-100 mb-2">
                            <i class="bx bx-download me-1"></i> Download PDF
                        </a>
                    @endif

                    <form method="POST" action="{{ route('admin.result-announcement.publications.destroy', $publication) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this publication?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bx bx-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Publication Settings -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Publication Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bx {{ $publication->allow_download ? 'bx-check text-success' : 'bx-x text-muted' }} me-2"></i>
                        Download Allowed
                    </div>
                    <div class="mb-2">
                        <i class="bx {{ $publication->require_authentication ? 'bx-check text-success' : 'bx-x text-muted' }} me-2"></i>
                        Authentication Required
                    </div>
                    <div class="mb-2">
                        <i class="bx {{ $publication->is_featured ? 'bx-check text-success' : 'bx-x text-muted' }} me-2"></i>
                        Featured Publication
                    </div>
                </div>
            </div>

            <!-- PDF Status -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">PDF Status</h6>
                </div>
                <div class="card-body">
                    @if($publication->pdf_file_path)
                        <div class="text-success">
                            <i class="bx bx-check-circle me-2"></i> PDF Generated
                        </div>
                        <small class="text-muted">File: {{ basename($publication->pdf_file_path) }}</small>
                    @else
                        <div class="text-muted">
                            <i class="bx bx-x-circle me-2"></i> PDF Not Generated
                        </div>
                        <small class="text-muted">Click "Generate PDF" to create</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
