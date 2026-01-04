@extends('teacher.layout.app')

@section('title', 'Grades')
@section('page-title', 'Grade Management')
@section('page-description', 'Manage student grades and assessments')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Grades</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $gradeStats['total_grades'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-graduation-cap text-white"></i>
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
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $gradeStats['published_grades'] }}">0</div>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Draft</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $gradeStats['draft_grades'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-info shadow h-100 py-2" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Average</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($gradeStats['average_grade'], 1) }}%</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-chart-line text-white"></i>
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
                        <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-plus me-2"></i>
                            Add Grade
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('teacher.grades.bulk-create') }}" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-layer-group me-2"></i>
                            Bulk Grades
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-info btn-lg w-100" onclick="showGradeChart()">
                            <i class="fas fa-chart-pie me-2"></i>
                            View Reports
                        </button>
                    </div>
                    <div class="col-md-3 mb-3">
                        <button class="btn btn-warning btn-lg w-100" onclick="exportGrades()">
                            <i class="fas fa-download me-2"></i>
                            Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grades Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grades List</h6>
                <div class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search grades...">
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
                    <table class="table table-bordered table-hover" id="gradesTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Student</th>
                                <th>Assignment</th>
                                <th>Type</th>
                                <th>Score</th>
                                <th>Grade</th>
                                <th>Class</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grades as $grade)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            {{ strtoupper(substr($grade->student->first_name ?? 'S', 0, 1)) }}{{ strtoupper(substr($grade->student->last_name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $grade->student->first_name ?? 'Unknown' }} {{ $grade->student->last_name ?? 'Student' }}</strong>
                                            <br><small class="text-muted">{{ $grade->student->student_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $grade->assignment_name }}</strong>
                                    @if($grade->comments)
                                        <br><small class="text-muted">{{ Str::limit($grade->comments, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($grade->assignment_type) }}</span>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <strong>{{ $grade->points_earned }}/{{ $grade->total_points }}</strong>
                                        <br><small class="text-muted">{{ number_format($grade->percentage, 1) }}%</small>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {!! $grade->grade_badge !!}
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $grade->class_name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $grade->subject_name }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $grade->graded_date->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    @if($grade->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($grade->status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teacher.grades.show', $grade) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher.grades.edit', $grade) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teacher.grades.destroy', $grade) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this grade?')">
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
                                <td colspan="10" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No grades found</h5>
                                        <p class="text-muted">Start by adding grades for your students.</p>
                                        <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>Add First Grade
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($grades->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $grades->links() }}
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
                <h5 class="modal-title">Filter Grades</h5>
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
                                <option value="published">Published</option>
                                <option value="draft">Draft</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type_filter" class="form-label">Assignment Type</label>
                            <select class="form-select" id="type_filter" name="type">
                                <option value="">All Types</option>
                                <option value="assignment">Assignment</option>
                                <option value="quiz">Quiz</option>
                                <option value="exam">Exam</option>
                                <option value="project">Project</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="grade_filter" class="form-label">Grade Range</label>
                            <select class="form-select" id="grade_filter" name="grade">
                                <option value="">All Grades</option>
                                <option value="A+">A+ (97-100%)</option>
                                <option value="A">A (93-96%)</option>
                                <option value="B+">B+ (87-92%)</option>
                                <option value="B">B (83-86%)</option>
                                <option value="C+">C+ (77-82%)</option>
                                <option value="C">C (73-76%)</option>
                                <option value="D">D (65-72%)</option>
                                <option value="F">F (Below 65%)</option>
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

<!-- Grade Chart Modal -->
<div class="modal fade" id="gradeChartModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Grade Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="gradeDistributionChart" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-6">
                        <canvas id="gradeTrendChart" width="400" height="400"></canvas>
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

// Show grade chart
function showGradeChart() {
    const modal = new bootstrap.Modal(document.getElementById('gradeChartModal'));
    modal.show();
    
    // Create grade distribution chart
    const distributionCtx = document.getElementById('gradeDistributionChart').getContext('2d');
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['A+', 'A', 'B+', 'B', 'C+', 'C', 'D', 'F'],
            datasets: [{
                data: [5, 10, 15, 20, 15, 10, 5, 2], // Sample data
                backgroundColor: [
                    '#28a745', '#20c997', '#17a2b8', '#007bff',
                    '#ffc107', '#fd7e14', '#6c757d', '#dc3545'
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
    
    // Create grade trend chart
    const trendCtx = document.getElementById('gradeTrendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
            datasets: [{
                label: 'Average Grade',
                data: [85, 87, 89, 88, 90],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
}

// Export grades
function exportGrades() {
    alert('Export functionality would be implemented here');
}

// Filter functionality
function applyFilters() {
    const classFilter = document.getElementById('class_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    const typeFilter = document.getElementById('type_filter').value;
    const gradeFilter = document.getElementById('grade_filter').value;
    
    const rows = document.querySelectorAll('#gradesTable tbody tr');
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        
        let showRow = true;
        
        if (classFilter && !row.cells[5].textContent.includes(classFilter)) {
            showRow = false;
        }
        
        if (statusFilter && !row.cells[8].textContent.toLowerCase().includes(statusFilter)) {
            showRow = false;
        }
        
        if (typeFilter && !row.cells[2].textContent.toLowerCase().includes(typeFilter)) {
            showRow = false;
        }
        
        if (gradeFilter && !row.cells[4].textContent.includes(gradeFilter)) {
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
    const rows = document.querySelectorAll('#gradesTable tbody tr');
    
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

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
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

.student-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9rem;
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
