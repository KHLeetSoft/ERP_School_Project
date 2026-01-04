@extends('parent.layout.app')

@section('title', 'Notices & Circulars')

@section('content')
<div class="page-header">
    <h1 class="page-title">Notices & Circulars</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notices & Circulars</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Notices List -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bullhorn me-2"></i>Recent Notices & Circulars
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">Annual Sports Day 2024</h6>
                                    <span class="badge badge-primary">Event</span>
                                </div>
                                <p class="text-muted mb-2">We are pleased to announce the Annual Sports Day will be held on December 25, 2024. All parents are cordially invited to attend this exciting event.</p>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        Dec 15, 2024
                                    </small>
                                    <small class="text-muted me-3">
                                        <i class="fas fa-user me-1"></i>
                                        School Administration
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>
                                        245 views
                                    </small>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewNotice(1)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">Winter Vacation Schedule</h6>
                                    <span class="badge badge-info">Holiday</span>
                                </div>
                                <p class="text-muted mb-2">School will be closed for winter vacation from December 20, 2024 to January 5, 2025. Classes will resume on January 6, 2025.</p>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        Dec 10, 2024
                                    </small>
                                    <small class="text-muted me-3">
                                        <i class="fas fa-user me-1"></i>
                                        Principal Office
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>
                                        189 views
                                    </small>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewNotice(2)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">Parent-Teacher Meeting</h6>
                                    <span class="badge badge-warning">Meeting</span>
                                </div>
                                <p class="text-muted mb-2">PTM is scheduled for December 18, 2024 from 2:00 PM to 5:00 PM. Please book your slot with the class teacher.</p>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        Dec 8, 2024
                                    </small>
                                    <small class="text-muted me-3">
                                        <i class="fas fa-user me-1"></i>
                                        Academic Department
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>
                                        156 views
                                    </small>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewNotice(3)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <h6 class="mb-0 me-2">Fee Payment Reminder</h6>
                                    <span class="badge badge-danger">Important</span>
                                </div>
                                <p class="text-muted mb-2">Please ensure all pending fees are paid by December 31, 2024 to avoid any late payment charges.</p>
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        Dec 5, 2024
                                    </small>
                                    <small class="text-muted me-3">
                                        <i class="fas fa-user me-1"></i>
                                        Accounts Department
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-eye me-1"></i>
                                        298 views
                                    </small>
                                </div>
                            </div>
                            <div class="ms-3">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewNotice(4)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Notice Categories -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-tags me-2"></i>Notice Categories
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Events & Activities</span>
                        <span class="badge badge-primary">5</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Holidays & Vacations</span>
                        <span class="badge badge-info">3</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Meetings & PTM</span>
                        <span class="badge badge-warning">2</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Important Announcements</span>
                        <span class="badge badge-danger">4</span>
                    </div>
                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span>Academic Updates</span>
                        <span class="badge badge-success">6</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary" onclick="filterNotices('all')">
                        <i class="fas fa-list me-2"></i>View All Notices
                    </button>
                    <button class="btn btn-outline-info" onclick="filterNotices('important')">
                        <i class="fas fa-exclamation-circle me-2"></i>Important Only
                    </button>
                    <button class="btn btn-outline-success" onclick="filterNotices('events')">
                        <i class="fas fa-calendar me-2"></i>Events & Activities
                    </button>
                    <button class="btn btn-outline-secondary" onclick="refreshNotices()">
                        <i class="fas fa-sync me-2"></i>Refresh Notices
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Notice Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar me-2"></i>Notice Statistics
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="stats-number">20</div>
                            <div class="stats-label">Total Notices</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">4</div>
                            <div class="stats-label">Important</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notice Modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="noticeModalLabel">Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="noticeContent">
                <!-- Notice content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadNotice()">Download PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function viewNotice(noticeId) {
        // Load notice content via AJAX
        fetch(`/parent/notices/${noticeId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('noticeContent').innerHTML = `
                    <div class="mb-3">
                        <h6>${data.title}</h6>
                        <p class="text-muted">
                            <i class="fas fa-calendar me-1"></i>Date: ${data.created_at}
                            <span class="ms-3">
                                <i class="fas fa-user me-1"></i>From: ${data.author}
                            </span>
                        </p>
                    </div>
                    <div class="border-top pt-3">
                        <p>${data.content}</p>
                    </div>
                `;
                
                // Show modal
                new bootstrap.Modal(document.getElementById('noticeModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading notice. Please try again.');
            });
    }
    
    function filterNotices(category) {
        // Implement notice filtering
        alert(`Filtering notices by: ${category}`);
    }
    
    function refreshNotices() {
        location.reload();
    }
    
    function downloadNotice() {
        alert('Download functionality will be implemented here.');
    }
</script>
@endpush
