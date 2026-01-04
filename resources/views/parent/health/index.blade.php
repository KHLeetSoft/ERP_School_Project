@extends('parent.layout.app')

@section('title', 'Health Records')

@section('content')
<div class="page-header">
    <h1 class="page-title">Health Records</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('parent.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Health Records</li>
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
                    
                    <!-- Health Stats -->
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-success mb-1">5'4"</div>
                                <small class="text-muted">Height</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <div class="h4 text-info mb-1">45kg</div>
                                <small class="text-muted">Weight</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="h4 text-warning mb-1">A+</div>
                            <small class="text-muted">Blood Group</small>
                        </div>
                    </div>
                    
                    <!-- Recent Health Records -->
                    <div class="mb-3">
                        <h6 class="mb-2">Recent Health Records</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Annual Health Checkup</h6>
                                        <small class="text-muted">Dec 10, 2024</small>
                                    </div>
                                    <span class="badge badge-success">Normal</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Vaccination - Flu Shot</h6>
                                        <small class="text-muted">Nov 15, 2024</small>
                                    </div>
                                    <span class="badge badge-info">Completed</span>
                                </div>
                            </div>
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 small">Eye Checkup</h6>
                                        <small class="text-muted">Oct 20, 2024</small>
                                    </div>
                                    <span class="badge badge-warning">Follow-up Required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('parent.health.child', $child) }}" class="btn btn-primary">
                            <i class="fas fa-heartbeat me-2"></i>View Health Details
                        </a>
                        <button class="btn btn-outline-success" onclick="addHealthRecord({{ $child->id }})">
                            <i class="fas fa-plus me-2"></i>Add Health Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-heartbeat fa-4x text-muted mb-4"></i>
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

<!-- Health Overview -->
@if($children->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-bar me-2"></i>Health Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #2ecc71, #27ae60);">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stats-number">12</div>
                            <div class="stats-label">Health Checkups</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #3498db, #2980b9);">
                                <i class="fas fa-syringe"></i>
                            </div>
                            <div class="stats-number">8</div>
                            <div class="stats-label">Vaccinations</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="stats-number">2</div>
                            <div class="stats-label">Follow-ups Required</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                                <i class="fas fa-ambulance"></i>
                            </div>
                            <div class="stats-number">0</div>
                            <div class="stats-label">Emergency Visits</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Health Guidelines -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-info-circle me-2"></i>Health Guidelines & Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Important Health Information:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Keep emergency contact numbers updated</li>
                            <li><i class="fas fa-check text-success me-2"></i>Inform school about any allergies</li>
                            <li><i class="fas fa-check text-success me-2"></i>Regular health checkups recommended</li>
                            <li><i class="fas fa-check text-success me-2"></i>Vaccination records must be current</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Emergency Procedures:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-info text-info me-2"></i>Contact school nurse: +91 98765 43210</li>
                            <li><i class="fas fa-info text-info me-2"></i>Emergency contact: +91 98765 43211</li>
                            <li><i class="fas fa-info text-info me-2"></i>School medical room: Room 101</li>
                            <li><i class="fas fa-info text-info me-2"></i>Nearest hospital: City General Hospital</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Health Record Modal -->
<div class="modal fade" id="addHealthRecordModal" tabindex="-1" aria-labelledby="addHealthRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHealthRecordModalLabel">Add Health Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addHealthRecordForm">
                    <div class="mb-3">
                        <label for="recordType" class="form-label">Record Type</label>
                        <select class="form-control" id="recordType" name="record_type" required>
                            <option value="">Select Record Type</option>
                            <option value="checkup">Health Checkup</option>
                            <option value="vaccination">Vaccination</option>
                            <option value="medication">Medication</option>
                            <option value="injury">Injury/Accident</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="recordDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="recordDate" name="record_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="recordDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="recordDescription" name="record_description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="recordNotes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="recordNotes" name="record_notes" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitHealthRecord()">Add Record</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function addHealthRecord(childId) {
        // Set child ID for health record
        document.getElementById('addHealthRecordForm').setAttribute('data-child-id', childId);
        
        // Show modal
        new bootstrap.Modal(document.getElementById('addHealthRecordModal')).show();
    }
    
    function submitHealthRecord() {
        const form = document.getElementById('addHealthRecordForm');
        const childId = form.getAttribute('data-child-id');
        const recordType = document.getElementById('recordType').value;
        const recordDate = document.getElementById('recordDate').value;
        const recordDescription = document.getElementById('recordDescription').value;
        
        if (!recordType || !recordDate || !recordDescription) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Process health record via AJAX
        alert('Health record addition functionality will be implemented here.');
        
        // Close modal
        bootstrap.Modal.getInstance(document.getElementById('addHealthRecordModal')).hide();
    }
</script>
@endpush
