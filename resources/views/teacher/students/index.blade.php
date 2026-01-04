@extends('teacher.layout.app')

@section('title', 'Students')
@section('page-title', 'My Students')
@section('page-description', 'Manage and view your students information')

@section('content')
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card border-left-primary shadow h-100 py-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $studentStats['total_students'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-users text-white"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $studentStats['active_students'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success">
                            <i class="fas fa-user-check text-white"></i>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Students</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $studentStats['inactive_students'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-user-times text-white"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Classes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800 counter" data-target="{{ $studentStats['classes_count'] }}">0</div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info">
                            <i class="fas fa-chalkboard text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Students Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Students List</h6>
                <div class="d-flex gap-2">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search students...">
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
                    <table class="table table-bordered table-hover" id="studentsTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Student</th>
                                <th>Student ID</th>
                                <th>Class</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Parent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="student-avatar me-3">
                                            {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                            @if($student->date_of_birth)
                                                <br><small class="text-muted">DOB: {{ \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $student->student_id }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $student->class_name }}</span>
                                </td>
                                <td>
                                    @if($student->email)
                                        <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                            {{ $student->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">No email</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->phone)
                                        <a href="tel:{{ $student->phone }}" class="text-decoration-none">
                                            {{ $student->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->parent_name)
                                        <div>
                                            <strong>{{ $student->parent_name }}</strong>
                                            @if($student->parent_phone)
                                                <br><small class="text-muted">{{ $student->parent_phone }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                                <td>
                                    @if($student->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('teacher.students.show', $student) }}" class="btn btn-info btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher.students.edit', $student) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-primary btn-sm" title="Contact" onclick="contactStudent('{{ $student->email }}', '{{ $student->phone }}')">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No students found</h5>
                                        <p class="text-muted">You don't have any students assigned to your classes yet.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Students</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="class_filter" class="form-label">Class</label>
                            <select class="form-select" id="class_filter" name="class">
                                <option value="">All Classes</option>
                                @foreach($students->groupBy('class_name') as $className => $classStudents)
                                    <option value="{{ $className }}">{{ $className }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter" name="status">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
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

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Contact Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-3">
                    <a href="#" id="emailLink" class="btn btn-primary">
                        <i class="fas fa-envelope me-2"></i>Send Email
                    </a>
                    <a href="#" id="phoneLink" class="btn btn-success">
                        <i class="fas fa-phone me-2"></i>Call
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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

// Search functionality
document.getElementById('searchBtn').addEventListener('click', function() {
    const query = document.getElementById('searchInput').value;
    if (query.trim()) {
        searchStudents(query);
    }
});

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        const query = this.value;
        if (query.trim()) {
            searchStudents(query);
        }
    }
});

function searchStudents(query) {
    fetch(`{{ route('teacher.students.search') }}?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            updateStudentsTable(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function updateStudentsTable(students) {
    const tbody = document.querySelector('#studentsTable tbody');
    tbody.innerHTML = '';
    
    if (students.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="empty-state">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No students found</h5>
                        <p class="text-muted">Try adjusting your search criteria.</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    students.forEach(student => {
        const row = createStudentRow(student);
        tbody.appendChild(row);
    });
}

function createStudentRow(student) {
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <div class="student-avatar me-3">
                    ${student.first_name.charAt(0).toUpperCase()}${student.last_name.charAt(0).toUpperCase()}
                </div>
                <div>
                    <strong>${student.first_name} ${student.last_name}</strong>
                    ${student.date_of_birth ? `<br><small class="text-muted">DOB: ${new Date(student.date_of_birth).toLocaleDateString()}</small>` : ''}
                </div>
            </div>
        </td>
        <td><span class="badge bg-secondary">${student.student_id}</span></td>
        <td><span class="badge bg-info">${student.class_name}</span></td>
        <td>${student.email ? `<a href="mailto:${student.email}" class="text-decoration-none">${student.email}</a>` : '<span class="text-muted">No email</span>'}</td>
        <td>${student.phone ? `<a href="tel:${student.phone}" class="text-decoration-none">${student.phone}</a>` : '<span class="text-muted">Not provided</span>'}</td>
        <td>${student.parent_name ? `<div><strong>${student.parent_name}</strong>${student.parent_phone ? `<br><small class="text-muted">${student.parent_phone}</small>` : ''}</div>` : '<span class="text-muted">Not provided</span>'}</td>
        <td><span class="badge ${student.status === 'active' ? 'bg-success' : 'bg-secondary'}">${student.status.charAt(0).toUpperCase() + student.status.slice(1)}</span></td>
        <td>
            <div class="btn-group" role="group">
                <a href="/teacher/students/${student.id}" class="btn btn-info btn-sm" title="View Details">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="/teacher/students/${student.id}/edit" class="btn btn-warning btn-sm" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
                <button class="btn btn-primary btn-sm" title="Contact" onclick="contactStudent('${student.email || ''}', '${student.phone || ''}')">
                    <i class="fas fa-envelope"></i>
                </button>
            </div>
        </td>
    `;
    return row;
}

// Filter functionality
function applyFilters() {
    const classFilter = document.getElementById('class_filter').value;
    const statusFilter = document.getElementById('status_filter').value;
    
    const rows = document.querySelectorAll('#studentsTable tbody tr');
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        
        let showRow = true;
        
        if (classFilter && !row.cells[2].textContent.includes(classFilter)) {
            showRow = false;
        }
        
        if (statusFilter && !row.cells[6].textContent.toLowerCase().includes(statusFilter)) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
    modal.hide();
}

// Contact functionality
function contactStudent(email, phone) {
    if (email) {
        document.getElementById('emailLink').href = `mailto:${email}`;
        document.getElementById('emailLink').style.display = 'block';
    } else {
        document.getElementById('emailLink').style.display = 'none';
    }
    
    if (phone) {
        document.getElementById('phoneLink').href = `tel:${phone}`;
        document.getElementById('phoneLink').style.display = 'block';
    } else {
        document.getElementById('phoneLink').style.display = 'none';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('contactModal'));
    modal.show();
}

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
