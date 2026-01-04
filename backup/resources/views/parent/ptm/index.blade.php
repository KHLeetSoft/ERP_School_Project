@extends('parent.layout.app')

@section('title', 'PTM Meetings')

@section('content')
<div class="page-header">
    <h1 class="page-title">Parent-Teacher Meetings</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">PTM Meetings</li>
        </ol>
    </nav>
</div>

<div class="row">
    @forelse($children as $child)
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $child->first_name }} {{ $child->last_name }}</h5>
                            <p class="text-muted mb-1">{{ $child->schoolClass->name ?? 'N/A' }} - {{ $child->section->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- PTM Stats -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-success mb-1">2</div>
                                <small class="text-muted">Attended</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-info mb-1">1</div>
                                <small class="text-muted">Scheduled</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-warning mb-1">0</div>
                            <small class="text-muted">Missed</small>
                        </div>
                    </div>
                    
                    <!-- Upcoming PTM -->
                    <div class="mb-3">
                        <h6 class="mb-2">Upcoming PTM</h6>
                        <div class="card border-info">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Monthly PTM</h6>
                                        <small class="text-muted">Dec 18, 2024 - 2:00 PM</small>
                                    </div>
                                    <span class="badge badge-info">Scheduled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent PTM -->
                    <div class="mb-3">
                        <h6 class="mb-2">Recent PTM</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Quarterly Review</h6>
                                        <small class="text-muted">Nov 15, 2024</small>
                                    </div>
                                    <span class="badge badge-success">Completed</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Progress Discussion</h6>
                                        <small class="text-muted">Oct 20, 2024</small>
                                    </div>
                                    <span class="badge badge-success">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="bookSlot({{ $child->id }})">
                            <i class="fas fa-calendar-plus me-2"></i>Book PTM Slot
                        </button>
                        <button class="btn btn-outline-info" onclick="viewFeedback({{ $child->id }})">
                            <i class="fas fa-comments me-2"></i>View Feedback
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-handshake fa-4x text-muted mb-4"></i>
                    <h4 class="text-muted">No Children Found</h4>
                    <p class="text-muted">You don't have any children registered in the system yet.</p>
                    <a href="{{ route('parent.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- PTM Overview -->
@if($children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-pie me-2"></i>PTM Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-number">6</div>
                            <div class="stats-label">Total Attended</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div class="stats-number">2</div>
                            <div class="stats-label">Scheduled</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stats-number">1</div>
                            <div class="stats-label">This Month</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="stats-number">0</div>
                            <div class="stats-label">Missed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Book PTM Slot Modal -->
<div class="modal fade" id="bookSlotModal" tabindex="-1" aria-labelledby="bookSlotModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookSlotModalLabel">Book PTM Slot</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bookSlotForm">
                    <div class="mb-3">
                        <label for="meetingDate" class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="meetingDate" name="meeting_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="meetingTime" class="form-label">Select Time Slot</label>
                        <select class="form-control" id="meetingTime" name="meeting_time" required>
                            <option value="">Select Time Slot</option>
                            <option value="09:00-09:30">9:00 AM - 9:30 AM</option>
                            <option value="09:30-10:00">9:30 AM - 10:00 AM</option>
                            <option value="10:00-10:30">10:00 AM - 10:30 AM</option>
                            <option value="10:30-11:00">10:30 AM - 11:00 AM</option>
                            <option value="11:00-11:30">11:00 AM - 11:30 AM</option>
                            <option value="11:30-12:00">11:30 AM - 12:00 PM</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="meetingPurpose" class="form-label">Purpose of Meeting</label>
                        <textarea class="form-control" id="meetingPurpose" name="meeting_purpose" rows="3" placeholder="Briefly describe what you'd like to discuss"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitBooking()">Book Slot</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function bookSlot(childId) {
        // Set child ID for booking
        document.getElementById('bookSlotForm').setAttribute('data-child-id', childId);
        
        // Show booking modal
        new bootstrap.Modal(document.getElementById('bookSlotModal')).show();
    }
    
    function submitBooking() {
        const form = document.getElementById('bookSlotForm');
        const childId = form.getAttribute('data-child-id');
        const meetingDate = document.getElementById('meetingDate').value;
        const meetingTime = document.getElementById('meetingTime').value;
        const meetingPurpose = document.getElementById('meetingPurpose').value;
        
        if (!meetingDate || !meetingTime) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Process booking via AJAX
        alert('PTM slot booking functionality will be implemented here.');
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('bookSlotModal')).hide();
    }
    
    function viewFeedback(childId) {
        alert('View feedback functionality will be implemented here.');
    }
</script>
@endpush
