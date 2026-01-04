@extends('teacher.layout.app')

@section('title', 'Assignments')
@section('page-title', 'Assignment Management')
@section('page-description', 'Create and manage assignments for your students')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Assignments</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $assignmentStats['total_assignments'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-tasks text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-success shadow h-100 py-2" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Published</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $assignmentStats['published_assignments'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-warning shadow h-100 py-2" data-aos="fade-up" data-aos-delay="300">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Upcoming</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $assignmentStats['upcoming_assignments'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-danger shadow h-100 py-2" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Overdue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $assignmentStats['overdue_assignments'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            Create Assignment
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.assignments.upcoming') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Upcoming
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.assignments.overdue') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Overdue
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-info btn-lg w-100" onclick="showAssignmentStats()">
                            <i class="fas fa-chart-pie me-2"></i>
                            Statistics
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assignments Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Assignments List</h6>
                <div class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search assignments...">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="assignmentsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Assignment</th>
                                <th>Class & Subject</th>
                                <th>Due Date</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Marks</th>
                                <th>Submissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                            <tr class="{{ $assignment->is_overdue ? 'table-danger' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="assignment-icon me-3">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $assignment->title }}</strong>
                                            <br><small class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                            @if($assignment->file)
                                                <br><small class="text-info"><i class="fas fa-paperclip me-1"></i>Has attachment</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge bg-info">{{ $assignment->class_section_name }}</span>
                                        <br><span class="badge bg-primary">{{ $assignment->subject_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $assignment->due_date_formatted }}</strong>
                                        <br><small class="text-muted">{{ $assignment->time_remaining }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {!! $assignment->priority_badge !!}
                                </td>
                                <td class="text-center">
                                    {!! $assignment->status_badge !!}
                                </td>
                                <td class="text-center">
                                    <div>
                                        <strong>{{ $assignment->max_marks }}</strong>
                                        <br><small class="text-muted">Pass: {{ $assignment->passing_marks }}</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="submission-stats">
                                        <span class="badge bg-success">{{ $assignment->submission_count }}</span>
                                        <br><small class="text-muted">Graded: {{ $assignment->graded_count }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teacher.assignments.show', $assignment) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher.assignments.edit', $assignment) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($assignment->status === 'draft')
                                            <form action="{{ route('teacher.assignments.publish', $assignment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Publish">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('teacher.assignments.destroy', $assignment) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this assignment?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No assignments found</h5>
                                        <p class="text-muted">Start by creating your first assignment.</p>
                                        <a href="{{ route('teacher.assignments.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Create First Assignment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($assignments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $assignments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Assignments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="class_filter" class="form-label">Class</label>
                            <select class="form-select" id="class_filter" name="class">
                                <option value="">All Classes</option>
                                @foreach($teacherClasses as $class)
                                    <option value="{{ $class->class_name }}">{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter" name="status">
                                <option value="">All Status</option>
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="assigned">Assigned</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="priority_filter" class="form-label">Priority</label>
                            <select class="form-select" id="priority_filter" name="priority">
                                <option value="">All Priorities</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_filter" class="form-label">Due Date</label>
                            <select class="form-select" id="date_filter" name="date">
                                <option value="">All Dates</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="overdue">Overdue</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="applyFilters()">Apply Filters</button>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Modal -->
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assignment Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="assignmentStatusChart" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="assignmentPriorityChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize AOS
AOS.init({
    duration: 1000,
    once: true
});

// Counter Animation
function animateCounters() {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current);
            }
        }, 16);
    });
}

// Initialize counters when page loads
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
});

// Show assignment statistics
function showAssignmentStats() {
    const modal = new bootstrap.Modal(document.getElementById('statsModal'));
    modal.show();
    
    // Create status chart
    const statusCtx = document.getElementById('assignmentStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Published', 'Draft', 'Assigned', 'Completed', 'Cancelled'],
            datasets: [{
                data: [{{ $assignmentStats['published_assignments'] }}, {{ $assignmentStats['draft_assignments'] }}, 0, 0, 0],
                backgroundColor: [
                    '#28a745', '#6c757d', '#007bff', '#17a2b8', '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
    
    // Create priority chart
    const priorityCtx = document.getElementById('assignmentPriorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'bar',
        data: {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{
                label: 'Assignments',
                data: [5, 8, 3], // Sample data
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Filter functionality
function applyFilters() {
    const classFilter = document.getElementById('class_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    const priorityFilter = document.getElementById('priority_filter').value;
    const dateFilter = document.getElementById('date_filter').value;
    
    const rows = document.querySelectorAll('#assignmentsTable tbody tr');
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        
        let showRow = true;
        
        if (classFilter && !row.cells[1].textContent.includes(classFilter)) {
            showRow = false;
        }
        
        if (statusFilter && !row.cells[4].textContent.toLowerCase().includes(statusFilter)) {
            showRow = false;
        }
        
        if (priorityFilter && !row.cells[3].textContent.toLowerCase().includes(priorityFilter)) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
    modal.hide();
}

// Search functionality
document.getElementById('searchBtn').addEventListener('click', function() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#assignmentsTable tbody tr');
    
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
});

// Add hover effects to cards
document.querySelectorAll('.stats-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
        this.style.transition = 'transform 0.3s ease';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.stats-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.stats-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.assignment-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.empty-state {
    padding: 2rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.submission-stats {
    text-align: center;
}

@media (max-width: 768px) {
    .icon-circle {
        width: 40px;
        height: 40px;
        font-size: 1rem;
    }
    
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
}
</style>
@endsection
