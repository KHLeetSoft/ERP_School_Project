@extends('student.layout.app')

@section('title', 'Complaints & Issues')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Complaints & Issues
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('student.hostel.index') }}">Hostel</a></li>
                        <li class="breadcrumb-item active">Complaints</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $complaintStats['total_complaints'] ?? 0 }}</h3>
                            <p class="mb-0">Total Complaints</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $complaintStats['open_complaints'] ?? 0 }}</h3>
                            <p class="mb-0">Open Complaints</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $complaintStats['resolved_complaints'] ?? 0 }}</h3>
                            <p class="mb-0">Resolved</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $complaintStats['average_resolution_time'] ?? 'N/A' }}</h3>
                            <p class="mb-0">Avg. Resolution Time</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hourglass-half fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit New Complaint -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Submit New Complaint
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.hostel.complaints.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" name="category" required>
                                        <option value="">Select Category</option>
                                        @if(isset($complaintCategories))
                                            @foreach($complaintCategories as $category)
                                                <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>
                                                    {{ $category }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                           name="subject" value="{{ old('subject') }}" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-select @error('priority') is-invalid @enderror" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" rows="4" required 
                                              placeholder="Please provide detailed description of the issue...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Attachments (Optional)</label>
                                    <input type="file" class="form-control @error('attachments') is-invalid @enderror" 
                                           name="attachments[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                    <small class="form-text text-muted">
                                        You can upload up to 3 files (Max 5MB each). Supported formats: JPG, PNG, PDF, DOC, DOCX
                                    </small>
                                    @error('attachments')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Complaint
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Complaints -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Your Complaints
                    </h5>
                </div>
                <div class="card-body">
                    @if(count($complaints) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Complaint ID</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assigned To</th>
                                        <th>Response</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complaints as $complaint)
                                    <tr>
                                        <td class="fw-bold">{{ $complaint['id'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($complaint['date'])->format('M d, Y') }}</td>
                                        <td>{{ $complaint['category'] }}</td>
                                        <td>{{ $complaint['subject'] }}</td>
                                        <td>
                                            <span class="badge bg-{{ $complaint['status'] === 'Resolved' ? 'success' : ($complaint['status'] === 'Open' ? 'warning' : 'secondary') }}">
                                                {{ $complaint['status'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $complaint['priority'] === 'High' ? 'danger' : ($complaint['priority'] === 'Medium' ? 'warning' : ($complaint['priority'] === 'Urgent' ? 'dark' : 'info')) }}">
                                                {{ ucfirst($complaint['priority']) }}
                                            </span>
                                        </td>
                                        <td>{{ $complaint['assigned_to'] ?? 'Not Assigned' }}</td>
                                        <td>
                                            @if($complaint['response'])
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Responded
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-clock"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewComplaintDetails('{{ $complaint['id'] }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No complaints found</h5>
                            <p class="text-muted">Your submitted complaints will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Guidelines -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle me-2"></i>Complaint Guidelines
                </h6>
                <ul class="mb-0">
                    <li>Please provide clear and detailed descriptions of your issues</li>
                    <li>Use appropriate priority levels - urgent for emergencies only</li>
                    <li>Attach relevant photos or documents when possible</li>
                    <li>Complaints are typically resolved within 2-3 business days</li>
                    <li>You will receive updates via email and SMS</li>
                    <li>For emergencies, contact the hostel office directly</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Complaint Details Modal -->
<div class="modal fade" id="complaintDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">Complaint Information</h6>
                        <p><strong>ID:</strong> <span id="complaintId">-</span></p>
                        <p><strong>Date:</strong> <span id="complaintDate">-</span></p>
                        <p><strong>Category:</strong> <span id="complaintCategory">-</span></p>
                        <p><strong>Subject:</strong> <span id="complaintSubject">-</span></p>
                        <p><strong>Priority:</strong> <span id="complaintPriority">-</span></p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">Status Information</h6>
                        <p><strong>Status:</strong> <span id="complaintStatus">-</span></p>
                        <p><strong>Assigned To:</strong> <span id="complaintAssignedTo">-</span></p>
                        <p><strong>Response:</strong> <span id="complaintResponse">-</span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-info">Description</h6>
                        <p id="complaintDescription">-</p>
                    </div>
                </div>
                <div class="row mt-3" id="complaintAttachments" style="display: none;">
                    <div class="col-12">
                        <h6 class="text-warning">Attachments</h6>
                        <div id="attachmentList">-</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printComplaint()">
                    <i class="fas fa-print me-2"></i>Print
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Mock complaint data
const complaintData = {
    'CMP-001': {
        id: 'CMP-001',
        date: 'March 15, 2024',
        category: 'WiFi Issues',
        subject: 'Internet connectivity problem',
        status: 'Open',
        priority: 'Medium',
        assignedTo: 'IT Support',
        response: 'We are investigating the issue and will update you soon.',
        description: 'The WiFi connection in my room has been very slow and frequently disconnects. This is affecting my online classes and assignments.',
        attachments: ['wifi_speed_test.jpg', 'room_location.pdf']
    },
    'CMP-002': {
        id: 'CMP-002',
        date: 'March 10, 2024',
        category: 'Room Maintenance',
        subject: 'Air conditioning not working',
        status: 'Resolved',
        priority: 'High',
        assignedTo: 'Maintenance Team',
        response: 'Issue resolved. AC unit replaced and tested.',
        description: 'The air conditioning unit in my room stopped working completely. The room is very hot and uncomfortable.',
        attachments: ['ac_unit_photo.jpg']
    }
};

function viewComplaintDetails(complaintId) {
    const complaint = complaintData[complaintId] || {
        id: complaintId,
        date: 'N/A',
        category: 'N/A',
        subject: 'N/A',
        status: 'Unknown',
        priority: 'N/A',
        assignedTo: 'N/A',
        response: 'No response available',
        description: 'No description available',
        attachments: []
    };

    // Populate modal with data
    document.getElementById('complaintId').textContent = complaint.id;
    document.getElementById('complaintDate').textContent = complaint.date;
    document.getElementById('complaintCategory').textContent = complaint.category;
    document.getElementById('complaintSubject').textContent = complaint.subject;
    document.getElementById('complaintPriority').innerHTML = `<span class="badge bg-${complaint.priority === 'High' ? 'danger' : 'warning'}">${complaint.priority}</span>`;
    document.getElementById('complaintStatus').innerHTML = `<span class="badge bg-${complaint.status === 'Resolved' ? 'success' : 'warning'}">${complaint.status}</span>`;
    document.getElementById('complaintAssignedTo').textContent = complaint.assignedTo;
    document.getElementById('complaintResponse').textContent = complaint.response;
    document.getElementById('complaintDescription').textContent = complaint.description;

    // Handle attachments
    if (complaint.attachments && complaint.attachments.length > 0) {
        document.getElementById('complaintAttachments').style.display = 'block';
        const attachmentList = document.getElementById('attachmentList');
        attachmentList.innerHTML = complaint.attachments.map(file => 
            `<a href="#" class="btn btn-sm btn-outline-primary me-2 mb-2">${file}</a>`
        ).join('');
    } else {
        document.getElementById('complaintAttachments').style.display = 'none';
    }

    const modal = new bootstrap.Modal(document.getElementById('complaintDetailsModal'));
    modal.show();
}

function printComplaint() {
    const printContent = document.querySelector('#complaintDetailsModal .modal-body').innerHTML;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Complaint Details</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .row { display: flex; margin-bottom: 10px; }
                    .col-md-6 { flex: 1; padding: 0 10px; }
                    h6 { color: #007bff; margin-bottom: 10px; }
                    p { margin: 5px 0; }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
