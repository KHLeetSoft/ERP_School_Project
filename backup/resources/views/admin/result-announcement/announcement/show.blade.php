@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bx bx-bullhorn me-2 text-primary"></i>Result Announcement Details</h4>
        <div>
            <a href="{{ route('admin.result-announcement.announcement.edit', $resultAnnouncement) }}" 
               class="btn btn-primary btn-sm me-2">
                <i class="bx bx-edit me-1"></i> Edit
            </a>
            <a href="{{ route('admin.result-announcement.announcement.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-arrow-back me-1"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Announcement Details -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $resultAnnouncement->title }}</h5>
                </div>
                <div class="card-body">
                    @if($resultAnnouncement->description)
                        <div class="mb-4">
                            <h6>Description</h6>
                            <p class="text-muted">{{ $resultAnnouncement->description }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Type:</strong>
                                <span class="badge bg-info ms-2">{{ $resultAnnouncement->announcement_type_text }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($resultAnnouncement->status === 'published')
                                    <span class="badge bg-success ms-2">Published</span>
                                @elseif($resultAnnouncement->status === 'draft')
                                    <span class="badge bg-secondary ms-2">Draft</span>
                                @else
                                    <span class="badge bg-warning ms-2">Archived</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <strong>Created By:</strong>
                                <span class="ms-2">{{ $resultAnnouncement->creator->name ?? 'N/A' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Created:</strong>
                                <span class="ms-2">{{ $resultAnnouncement->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            @if($resultAnnouncement->exam)
                                <div class="mb-3">
                                    <strong>Exam:</strong>
                                    <span class="ms-2">{{ $resultAnnouncement->exam->title }}</span>
                                </div>
                            @endif

                            @if($resultAnnouncement->onlineExam)
                                <div class="mb-3">
                                    <strong>Online Exam:</strong>
                                    <span class="ms-2">{{ $resultAnnouncement->onlineExam->title }}</span>
                                </div>
                            @endif

                            @if($resultAnnouncement->publish_at)
                                <div class="mb-3">
                                    <strong>Publish Date:</strong>
                                    <span class="ms-2">{{ $resultAnnouncement->publish_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif

                            @if($resultAnnouncement->expires_at)
                                <div class="mb-3">
                                    <strong>Expiry Date:</strong>
                                    <span class="ms-2">{{ $resultAnnouncement->expires_at->format('M d, Y h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Target Audience -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Target Audience</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Audience Type</h6>
                            <p>{{ $resultAnnouncement->target_audience_text }}</p>
                        </div>
                        
                        @if($resultAnnouncement->class_ids)
                            <div class="col-md-6">
                                <h6>Specific Classes</h6>
                                <p>
                                    @foreach($resultAnnouncement->class_ids as $classId)
                                        @php
                                            $class = \App\Models\SchoolClass::find($classId);
                                        @endphp
                                        @if($class)
                                            <span class="badge bg-light text-dark me-1">{{ $class->name }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                        @endif

                        @if($resultAnnouncement->section_ids)
                            <div class="col-md-6">
                                <h6>Specific Sections</h6>
                                <p>
                                    @foreach($resultAnnouncement->section_ids as $sectionId)
                                        @php
                                            $section = \App\Models\Section::find($sectionId);
                                        @endphp
                                        @if($section)
                                            <span class="badge bg-light text-dark me-1">{{ $section->name }}</span>
                                        @endif
                                    @endforeach
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($resultAnnouncement->status === 'draft')
                        <form method="POST" action="{{ route('admin.result-announcement.announcement.publish', $resultAnnouncement) }}" 
                              class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bx bx-publish me-1"></i> Publish Now
                            </button>
                        </form>
                    @endif

                    @if($resultAnnouncement->status === 'published')
                        <form method="POST" action="{{ route('admin.result-announcement.announcement.archive', $resultAnnouncement) }}" 
                              class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="bx bx-archive me-1"></i> Archive
                            </button>
                        </form>
                    @endif

                    @if($resultAnnouncement->status === 'published' && 
                        ($resultAnnouncement->send_sms || $resultAnnouncement->send_email || $resultAnnouncement->send_push_notification))
                        <form method="POST" action="{{ route('admin.result-announcement.announcement.send-notifications', $resultAnnouncement) }}" 
                              class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-info w-100">
                                <i class="bx bx-send me-1"></i> Send Notifications
                            </button>
                        </form>
                    @endif

                    <form method="POST" action="{{ route('admin.result-announcement.announcement.destroy', $resultAnnouncement) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bx bx-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Notification Settings</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <i class="bx {{ $resultAnnouncement->send_sms ? 'bx-check text-success' : 'bx-x text-muted' }} me-2"></i>
                        SMS Notifications
                    </div>
                    <div class="mb-2">
                        <i class="bx {{ $resultAnnouncement->send_email ? 'bx-check text-success' : 'bx-x text-muted' }} me-2"></i>
                        Email Notifications
                    </div>
                    <div class="mb-2">
                        <i class="bx {{ $resultAnnouncement->send_push_notification ? 'bx-check text-success' : 'bx-x text-muted' }} me-2"></i>
                        Push Notifications
                    </div>
                </div>
            </div>

            <!-- Activity Status -->
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h6 class="mb-0">Activity Status</h6>
                </div>
                <div class="card-body">
                    @if($resultAnnouncement->isActive())
                        <div class="text-success">
                            <i class="bx bx-check-circle me-2"></i> Active
                        </div>
                        <small class="text-muted">This announcement is currently visible to users</small>
                    @else
                        <div class="text-muted">
                            <i class="bx bx-x-circle me-2"></i> Inactive
                        </div>
                        <small class="text-muted">This announcement is not currently visible</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
