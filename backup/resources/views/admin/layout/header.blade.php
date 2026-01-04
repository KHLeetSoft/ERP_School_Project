<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4 rounded shadow-sm">
    <div class="container-fluid">
        <!-- Brand/Logo -->
        <a class="navbar-brand fw-bold text-primary" href="{{ url('admin/dashboard') }}">
            <i class="fas fa-graduation-cap me-2"></i>School Admin
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left side - Search -->
            <div class="navbar-nav me-auto">
                <div class="nav-item">
                    <div class="input-group" style="width: 300px;">
                        <input type="text" class="form-control" placeholder="Search students, teachers..." id="globalSearch">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right side - Notifications, Messages, Profile -->
            <div class="navbar-nav ms-auto">
                <!-- Quick Actions Dropdown -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-plus-circle me-1"></i>Quick Actions
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ url('admin/users/students/create') }}">
                            <i class="fas fa-user-plus me-2"></i>Add Student
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/users/teachers/create') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>Add Teacher
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/exams/exam/create') }}">
                            <i class="fas fa-clipboard-list me-2"></i>Create Exam
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/finance/invoice/create') }}">
                            <i class="fas fa-file-invoice me-2"></i>Generate Invoice
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('admin/reports') }}">
                            <i class="fas fa-chart-bar me-2"></i>View Reports
                        </a></li>
                    </ul>
                </div>

                <!-- Notifications Dropdown -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <li class="dropdown-header">
                            <i class="fas fa-bell me-2"></i>Notifications
                            <span class="badge bg-primary ms-2">3 New</span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-plus text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="fw-bold">New Student Registered</div>
                                        <small class="text-muted">John Doe enrolled in Class 10A</small>
                                        <small class="text-muted d-block">2 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="fw-bold">Fee Payment Due</div>
                                        <small class="text-muted">15 students have pending fees</small>
                                        <small class="text-muted d-block">1 hour ago</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="fw-bold">Exam Schedule</div>
                                        <small class="text-muted">Mid-term exams starting tomorrow</small>
                                        <small class="text-muted d-block">3 hours ago</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="{{ url('admin/notifications') }}">
                            View All Notifications
                        </a></li>
                    </ul>
                </div>

                <!-- Messages Dropdown -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-envelope"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            5
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                        <li class="dropdown-header">
                            <i class="fas fa-envelope me-2"></i>Messages
                            <span class="badge bg-success ms-2">5 Unread</span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="https://i.pravatar.cc/40?img=1" class="rounded-circle" width="40" height="40">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="fw-bold">Parent Inquiry</div>
                                        <small class="text-muted">Regarding student performance...</small>
                                        <small class="text-muted d-block">10 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="https://i.pravatar.cc/40?img=2" class="rounded-circle" width="40" height="40">
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="fw-bold">Teacher Request</div>
                                        <small class="text-muted">Leave application submitted</small>
                                        <small class="text-muted d-block">1 hour ago</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="{{ url('admin/messages') }}">
                            View All Messages
                        </a></li>
                    </ul>
                </div>

                <!-- Settings Dropdown -->
                <div class="nav-item dropdown me-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <i class="fas fa-cog me-2"></i>Settings
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('admin/settings/profile') }}">
                            <i class="fas fa-user me-2"></i>Profile Settings
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/settings/theme') }}">
                            <i class="fas fa-palette me-2"></i>Theme Settings
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/settings/notifications') }}">
                            <i class="fas fa-bell me-2"></i>Notification Settings
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/settings/security') }}">
                            <i class="fas fa-shield-alt me-2"></i>Security Settings
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('admin/help') }}">
                            <i class="fas fa-question-circle me-2"></i>Help & Support
                        </a></li>
                    </ul>
                </div>

                <!-- User Profile Dropdown -->
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <img src="https://i.pravatar.cc/40?img=3" class="rounded-circle me-2" width="32" height="32">
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">
                            <div class="text-center">
                                <img src="https://i.pravatar.cc/60?img=3" class="rounded-circle mb-2" width="50" height="50">
                                <div class="fw-bold">{{ Auth::user()->name ?? 'Admin' }}</div>
                                <small class="text-muted">Administrator</small>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('admin/profile') }}">
                            <i class="fas fa-user me-2"></i>My Profile
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/settings') }}">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a></li>
                        <li><a class="dropdown-item" href="{{ url('admin/activity-log') }}">
                            <i class="fas fa-history me-2"></i>Activity Log
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                             <form method="POST" action="{{ route('admin.logout') }}" 
                                style="display: inline;" 
                                onsubmit="return confirm('Are you sure you want to logout?');">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>


<!-- Global Search Results Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Search Results</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="searchResults">
                    <!-- Search results will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.navbar-brand {
    font-size: 1.5rem;
}

.navbar-nav .nav-link {
    padding: 0.5rem 1rem;
    color: #495057;
    transition: color 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #007bff;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 0.5rem;
}

.dropdown-item {
    padding: 0.75rem 1rem;
    transition: background-color 0.3s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

.dropdown-header {
    padding: 0.5rem 1rem;
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.input-group .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.badge {
    font-size: 0.7rem;
}

@media (max-width: 768px) {
    .input-group {
        width: 100% !important;
        margin-top: 0.5rem;
    }
    
    .navbar-nav .nav-item {
        margin: 0.25rem 0;
    }
}
</style>

<script>
$(document).ready(function() {
    // Global search functionality
    $('#globalSearch').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 2) {
            // Simulate search - replace with actual AJAX call
            console.log('Searching for:', query);
            // You can implement actual search functionality here
        }
    });

    // Auto-hide dropdowns when clicking outside
    $('.dropdown-menu').on('click', function(e) {
        e.stopPropagation();
    });

    // Mark notifications as read
    $('.dropdown-item').on('click', function() {
        // Add logic to mark notifications as read
        console.log('Notification clicked');
    });
});
</script>
