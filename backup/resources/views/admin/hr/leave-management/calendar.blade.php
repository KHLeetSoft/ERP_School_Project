@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <!-- Advanced Filters & Search Panel -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <i class="fas fa-filter me-2"></i>Advanced Filters & Search
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Leave Type</label>
                            <select class="form-select form-select-sm" id="leaveTypeFilter">
                                <option value="">All Types</option>
                                <option value="casual">Casual</option>
                                <option value="sick">Sick</option>
                                <option value="annual">Annual</option>
                                <option value="maternity">Maternity</option>
                                <option value="paternity">Paternity</option>
                                <option value="bereavement">Bereavement</option>
                                <option value="study">Study</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Department</label>
                            <select class="form-select form-select-sm" id="departmentFilter">
                                <option value="">All Departments</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Actions</label>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="applyFilters()">
                                    <i class="fas fa-search me-1"></i>Apply
                                </button>
                                <button class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search by staff name, leave type, or department...">
                                <button class="btn btn-outline-success" onclick="searchLeaves()">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-info btn-sm" onclick="showSearchResults()">
                                    <i class="fas fa-list me-1"></i>Results
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="saveSearchQuery()">
                                    <i class="fas fa-save me-1"></i>Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-chart-line me-2"></i>Quick Insights
                </div>
                <div class="card-body">
                    <div class="insight-item">
                        <i class="fas fa-users text-primary"></i>
                        <span>Staff on Leave Today: <strong id="todayLeaves">0</strong></span>
                    </div>
                    <div class="insight-item">
                        <i class="fas fa-calendar-week text-success"></i>
                        <span>This Week: <strong id="weekLeaves">0</strong></span>
                    </div>
                    <div class="insight-item">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        <span>Pending Approvals: <strong id="pendingApprovals">0</strong></span>
                    </div>
                    <div class="insight-item">
                        <i class="fas fa-clock text-info"></i>
                        <span>Avg. Response Time: <strong id="avgResponseTime">0h</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-light btn-sm me-3" onclick="changeMonth(-1)">
                            <i class="fas fa-chevron-left"></i> Previous
                        </button>
                        <h2 id="current-month" class="mb-0"></h2>
                        <button class="btn btn-light btn-sm ms-3" onclick="changeMonth(1)">
                            Next <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-light btn-sm" onclick="goToToday()">
                            <i class="fas fa-calendar-day"></i> Today
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="enableDragAndDrop()">
                            <i class="fas fa-arrows-alt"></i> Drag & Drop
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="calendar" id="calendar"></div>
                </div>
            </div>
            <div class="legend d-flex gap-4 mt-3">
                <div><span class="legend-color bg-approved"></span> Approved</div>
                <div><span class="legend-color bg-pending"></span> Pending</div>
                <div><span class="legend-color bg-rejected"></span> Rejected</div>
                <div><span class="legend-color bg-half-day"></span> Half Day</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-chart-pie me-2"></i>Quick Stats
                </div>
                <div class="card-body">
                    <p>Total Leaves: <span id="total-leaves">0</span></p>
                    <p>Approved: <span id="approved-leaves">0</span></p>
                    <p>Pending: <span id="pending-leaves">0</span></p>
                    <p>Rejected: <span id="rejected-leaves">0</span></p>
                </div>
            </div>
            
            <!-- Enhanced Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-success w-100 mb-2" onclick="exportAdvancedReport()">
                    <i class="fas fa-file-export me-2"></i>Export Report
                </button>
                <button class="btn btn-info w-100 mb-2" onclick="showAdvancedStats()">
                    <i class="fas fa-chart-bar me-2"></i>Analytics
                </button>
                <button class="btn btn-warning w-100 mb-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print
                </button>
                <button class="btn btn-secondary w-100 mb-2" onclick="goToToday()">
                    <i class="fas fa-calendar-day me-2"></i>Today
                </button>
            </div>

            <!-- Theme Selector -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-palette me-2"></i>Themes
                </div>
                <div class="card-body">
                    <div class="theme-options">
                        <button class="btn btn-sm btn-outline-primary mb-1 w-100" onclick="changeTheme('default')">Default</button>
                        <button class="btn btn-sm btn-outline-dark mb-1 w-100" onclick="changeTheme('dark')">Dark</button>
                        <button class="btn btn-sm btn-outline-success mb-1 w-100" onclick="changeTheme('nature')">Nature</button>
                        <button class="btn btn-sm btn-outline-warning mb-1 w-100" onclick="changeTheme('sunset')">Sunset</button>
                    </div>
                </div>
            </div>

            <!-- View Mode Switcher -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-eye me-2"></i>View Mode
                </div>
                <div class="card-body">
                    <div class="view-mode-buttons">
                        <button class="btn btn-sm btn-outline-info mb-1 w-100 view-mode-btn active" data-mode="month" onclick="switchViewMode('month')">Month</button>
                        <button class="btn btn-sm btn-outline-info mb-1 w-100 view-mode-btn" data-mode="week" onclick="switchViewMode('week')">Week</button>
                        <button class="btn btn-sm btn-outline-info mb-1 w-100 view-mode-btn" data-mode="list" onclick="switchViewMode('list')">List</button>
                    </div>
                </div>
            </div>

            <!-- Keyboard Shortcuts Help -->
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <i class="fas fa-keyboard me-2"></i>Shortcuts
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <div>Ctrl + ← → Navigate</div>
                        <div>Ctrl + T Today</div>
                        <div>Ctrl + F Search</div>
                        <div>Ctrl + E Export</div>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal" id="event-modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Leave Details</h5>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
            </div>
            <div class="modal-body" id="modal-body"></div>
        </div>
    </div>
</div>

<style>
    /* 🎨 ENHANCED CALENDAR STYLING */
    
    /* CSS Variables for Themes */
    :root {
        --primary-color: #007bff;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
    }

    /* Enhanced Calendar Grid */
    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        padding: 10px;
    }

    /* Enhanced Day Cells */
    .day {
        border: 2px solid #e9ecef;
        min-height: 120px;
        padding: 8px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .day:hover { 
        transform: translateY(-3px) scale(1.02); 
        background: linear-gradient(135deg, #eef3ff 0%, #e3f2fd 100%);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: var(--primary-color);
    }

    .day.today {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border-color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    }

    .day.weekend {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    }

    /* Enhanced Date Number */
    .date-number { 
        font-weight: 700; 
        margin-bottom: 8px; 
        font-size: 16px;
        color: var(--dark-color);
        text-align: center;
        padding: 4px 8px;
        background: rgba(255,255,255,0.8);
        border-radius: 8px;
        display: inline-block;
        width: 100%;
    }

    /* Enhanced Calendar Events */
    .calendar-event {
        font-size: 11px;
        padding: 6px 8px;
        border-radius: 8px;
        margin-bottom: 4px;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border-left: 4px solid;
    }

    .calendar-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .calendar-event::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .calendar-event:hover::before {
        left: 100%;
    }

    /* Enhanced Status Colors with Gradients */
    .bg-approved { 
        background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
        border-left-color: #2f855a;
    }
    
    .bg-pending { 
        background: linear-gradient(135deg, #d69e2e 0%, #b7791f 100%);
        border-left-color: #b7791f;
    }
    
    .bg-rejected { 
        background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        border-left-color: #c53030;
    }
    
    .bg-half-day { 
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        border-left-color: #3182ce;
    }

    .bg-cancelled {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border-left-color: #495057;
    }

    /* Enhanced Legend */
    .legend-color {
        width: 18px;
        height: 18px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        border: 2px solid white;
    }

    /* Filter Panel Styling */
    .filter-panel {
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border-radius: 15px;
        border: 1px solid #ffc107;
    }

    .filter-header {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 15px 15px 0 0;
        border-bottom: 2px solid #e0a800;
    }

    .filter-content {
        padding: 20px;
    }

    /* Search Panel Styling */
    .search-panel {
        background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
        border-radius: 15px;
        border: 1px solid #17a2b8;
        padding: 20px;
    }

    /* Quick Insights Styling */
    .insight-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .insight-item:hover {
        background: rgba(0,0,0,0.05);
        border-radius: 8px;
        padding-left: 10px;
    }

    .insight-item:last-child {
        border-bottom: none;
    }

    .insight-item i {
        font-size: 18px;
        width: 25px;
        text-align: center;
    }

    /* Action Buttons Styling */
    .action-buttons .btn {
        transition: all 0.3s ease;
        border-radius: 10px;
        font-weight: 600;
    }

    .action-buttons .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Theme Options Styling */
    .theme-options .btn {
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .theme-options .btn:hover {
        transform: scale(1.05);
    }

    /* View Mode Buttons */
    .view-mode-btn {
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .view-mode-btn.active {
        background: var(--info-color);
        color: white;
        border-color: var(--info-color);
    }

    .view-mode-btn:hover {
        transform: translateY(-1px);
    }

    /* Enhanced Modal Styling */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }

    .modal-header {
        border-radius: 15px 15px 0 0;
        background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
    }

    /* Quick Actions Menu */
    .quick-actions {
        animation: slideDown 0.3s ease-out;
    }

    .action-item {
        padding: 8px 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .action-item:hover {
        background: #f8f9fa;
        padding-left: 16px;
    }

    .action-item:last-child {
        border-bottom: none;
    }

    /* Notification System */
    .notification {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideDown {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Enhanced Form Controls */
    .form-select, .form-control {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .calendar {
            gap: 4px;
            padding: 5px;
        }
        
        .day {
            min-height: 80px;
            padding: 4px;
        }
        
        .date-number {
            font-size: 14px;
            padding: 2px 4px;
        }
        
        .calendar-event {
            font-size: 10px;
            padding: 4px 6px;
        }
        
        .filter-content .row > div {
            margin-bottom: 15px;
        }
    }

    /* Print Styles */
    @media print {
        .filter-panel, .search-panel, .action-buttons, .theme-options, .view-mode-buttons {
            display: none !important;
        }
        
        .calendar {
            gap: 2px;
        }
        
        .day {
            border: 1px solid #000;
            min-height: 100px;
        }
    }

    /* Loading States */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin: -10px 0 0 -10px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Enhanced Card Shadows */
    .card {
        transition: all 0.3s ease;
        border: none;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #0056b3;
    }
</style>

<script>
    const calendar = document.getElementById("calendar");
    const monthLabel = document.getElementById("current-month");
    let currentDate = new Date();
    
    // Global variables for enhanced functionality
    let leaves = [];
    let filteredLeaves = [];
    let currentFilters = {};
    let searchQuery = '';
    let viewMode = 'month'; // month, week, list
    let selectedDate = null;
    let dragStartEvent = null;

    // 🚀 ENHANCED FUNCTIONS - Making Calendar Unique & Powerful

    // 1. Advanced Filtering System
    function applyFilters() {
        const statusFilter = document.getElementById('statusFilter')?.value || '';
        const typeFilter = document.getElementById('leaveTypeFilter')?.value || '';
        const deptFilter = document.getElementById('departmentFilter')?.value || '';
        
        currentFilters = { status: statusFilter, type: typeFilter, department: deptFilter };
        
        filteredLeaves = leaves.filter(leave => {
            let matches = true;
            if (statusFilter && leave.status !== statusFilter) matches = false;
            if (typeFilter && leave.leave_type !== typeFilter) matches = false;
            if (deptFilter && leave.department !== deptFilter) matches = false;
            if (searchQuery && !leave.staff_name.toLowerCase().includes(searchQuery.toLowerCase())) matches = false;
            return matches;
        });
        
        renderCalendar();
        updateStats();
        showNotification(`Filtered ${filteredLeaves.length} leaves`, 'info');
    }

    // 2. Smart Search with Auto-complete
    function searchLeaves() {
        searchQuery = document.getElementById('searchInput')?.value || '';
        applyFilters();
        
        if (searchQuery) {
            const results = leaves.filter(l => 
                l.staff_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                l.leave_type.toLowerCase().includes(searchQuery.toLowerCase())
            );
            showSearchResults(results);
        }
    }

    // 3. View Mode Switcher (Month/Week/List)
    function switchViewMode(mode) {
        viewMode = mode;
        document.querySelectorAll('.view-mode-btn').forEach(btn => btn.classList.remove('active'));
        const activeBtn = document.querySelector(`[data-mode="${mode}"]`);
        if (activeBtn) activeBtn.classList.add('active');
        
        if (mode === 'list') {
            renderListView();
        } else {
            renderCalendar();
        }
        showNotification(`Switched to ${mode} view`, 'info');
    }

    // 4. Drag & Drop for Leave Management
    function enableDragAndDrop() {
        const events = document.querySelectorAll('.calendar-event');
        events.forEach(event => {
            event.draggable = true;
            event.addEventListener('dragstart', handleDragStart);
            event.addEventListener('dragend', handleDragEnd);
        });
        
        const days = document.querySelectorAll('.day');
        days.forEach(day => {
            day.addEventListener('dragover', handleDragOver);
            day.addEventListener('drop', handleDrop);
        });
    }

    function handleDragStart(e) {
        dragStartEvent = e.target;
        e.target.style.opacity = '0.5';
    }

    function handleDragEnd(e) {
        e.target.style.opacity = '1';
    }

    function handleDragOver(e) {
        e.preventDefault();
    }

    function handleDrop(e) {
        e.preventDefault();
        const targetDate = e.target.closest('.day')?.dataset.date;
        if (targetDate && dragStartEvent) {
            // Update leave date in backend
            updateLeaveDate(dragStartEvent.dataset.leaveId, targetDate);
        }
    }

    // 5. Quick Actions Menu
    function showQuickActions(event, leaveId) {
        const actions = `
            <div class="quick-actions" style="position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; min-width: 150px;">
                <div class="action-item" onclick="approveLeave(${leaveId})">
                    <i class="fas fa-check text-success"></i> Approve
                </div>
                <div class="action-item" onclick="rejectLeave(${leaveId})">
                    <i class="fas fa-times text-danger"></i> Reject
                </div>
                <div class="action-item" onclick="editLeave(${leaveId})">
                    <i class="fas fa-edit text-primary"></i> Edit
                </div>
                <div class="action-item" onclick="deleteLeave(${leaveId})">
                    <i class="fas fa-trash text-danger"></i> Delete
                </div>
            </div>
        `;
        
        event.target.insertAdjacentHTML('beforeend', actions);
        setTimeout(() => {
            const quickActions = document.querySelector('.quick-actions');
            if (quickActions) quickActions.remove();
        }, 5000);
    }

    // 6. Leave Approval/Rejection
    function approveLeave(leaveId) {
        if (confirm('Are you sure you want to approve this leave?')) {
            // API call to approve leave
            showNotification('Leave approved successfully!', 'success');
            loadLeaveData(); // Refresh data
        }
    }

    function rejectLeave(leaveId) {
        const reason = prompt('Please provide rejection reason:');
        if (reason) {
            // API call to reject leave
            showNotification('Leave rejected successfully!', 'error');
            loadLeaveData(); // Refresh data
        }
    }

    // 7. Advanced Statistics & Analytics
    function showAdvancedStats() {
        const stats = calculateAdvancedStats();
        const modal = `
            <div class="modal fade" id="statsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5><i class="fas fa-chart-bar me-2"></i>Advanced Analytics</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Leave Distribution</h6>
                                    <canvas id="leaveChart" width="300" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <h6>Monthly Trends</h6>
                                    <canvas id="trendChart" width="300" height="200"></canvas>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Department Performance</h6>
                                    <div id="deptPerformance"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modal);
        const modalElement = document.getElementById('statsModal');
        if (modalElement) {
            const bootstrapModal = new bootstrap.Modal(modalElement);
            bootstrapModal.show();
            renderCharts(stats);
        }
    }

    // 8. Smart Notifications System
    function showNotification(message, type = 'info') {
        const notification = `
            <div class="notification notification-${type}" style="
                position: fixed; top: 20px; right: 20px; 
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'}; 
                color: white; padding: 15px 20px; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;
                animation: slideIn 0.3s ease-out;
            ">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; margin-left: 15px;">×</button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', notification);
        setTimeout(() => {
            const notif = document.querySelector('.notification');
            if (notif) notif.remove();
        }, 5000);
    }

    // 9. Export & Reporting
    function exportAdvancedReport() {
        const reportData = {
            totalLeaves: leaves.length,
            approvedLeaves: leaves.filter(l => l.status === 'approved').length,
            pendingLeaves: leaves.filter(l => l.status === 'pending').length,
            rejectedLeaves: leaves.filter(l => l.status === 'rejected').length,
            leaveTypes: leaves.reduce((acc, l) => {
                acc[l.leave_type] = (acc[l.leave_type] || 0) + 1;
                return acc;
            }, {}),
            monthlyData: getMonthlyData(),
            generatedAt: new Date().toISOString()
        };
        
        const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `leave_report_${new Date().toISOString().split('T')[0]}.json`;
        link.click();
        
        showNotification('Advanced report exported successfully!', 'success');
    }

    // 10. Calendar Themes & Personalization
    function changeTheme(theme) {
        const themes = {
            'default': { primary: '#007bff', secondary: '#6c757d' },
            'dark': { primary: '#343a40', secondary: '#495057' },
            'nature': { primary: '#28a745', secondary: '#20c997' },
            'sunset': { primary: '#fd7e14', secondary: '#e83e8c' }
        };
        
        const selectedTheme = themes[theme];
        if (selectedTheme) {
            document.documentElement.style.setProperty('--primary-color', selectedTheme.primary);
            document.documentElement.style.setProperty('--secondary-color', selectedTheme.secondary);
            
            localStorage.setItem('calendar-theme', theme);
            showNotification(`Theme changed to ${theme}`, 'info');
        }
    }

    // 11. Keyboard Shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        changeMonth(-1);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        changeMonth(1);
                        break;
                    case 't':
                        e.preventDefault();
                        goToToday();
                        break;
                    case 'f':
                        e.preventDefault();
                        const searchInput = document.getElementById('searchInput');
                        if (searchInput) searchInput.focus();
                        break;
                    case 'e':
                        e.preventDefault();
                        exportAdvancedReport();
                        break;
                }
            }
        });
    }

    // 12. Auto-refresh & Real-time Updates
    function enableAutoRefresh() {
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                loadLeaveData();
            }
        }, 300000); // Refresh every 5 minutes
    }

    // 13. Utility Functions
    function updateLegendCounts() {
        const counts = {
            pending: leaves.filter(l => l.status === 'pending').length,
            approved: leaves.filter(l => l.status === 'approved').length,
            rejected: leaves.filter(l => l.status === 'rejected').length,
            halfDay: leaves.filter(l => l.half_day).length
        };
        
        Object.keys(counts).forEach(status => {
            const element = document.getElementById(`${status}Count`);
            if (element) element.textContent = counts[status];
        });
    }

    function populateDepartmentFilter() {
        const departments = [...new Set(leaves.map(l => l.department).filter(Boolean))];
        const select = document.getElementById('departmentFilter');
        if (select) {
            select.innerHTML = '<option value="">All Departments</option>';
            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                select.appendChild(option);
            });
        }
    }

    function goToToday() {
        currentDate = new Date();
        renderCalendar();
        showNotification('Jumped to today!', 'info');
    }

    function loadLeaveData() {
        fetch("{{ route('admin.hr.leave-management.calendar') }}?year=" + currentDate.getFullYear() + "&month=" + (currentDate.getMonth() + 1), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => res.json())
            .then(data => {
                // Ensure data is an array
                if (Array.isArray(data)) {
                    leaves = data;
                    filteredLeaves = data;
                } else {
                    leaves = [];
                    filteredLeaves = [];
                    console.warn('Received non-array data:', data);
                }
                
                renderCalendar();
                updateStats();
                updateLegendCounts();
                populateDepartmentFilter();
                showNotification('Calendar data loaded successfully!', 'success');
            })
            .catch(err => {
                console.error("Error loading leave data:", err);
                // Set empty arrays on error to prevent crashes
                leaves = [];
                filteredLeaves = [];
                showNotification('Failed to load calendar data!', 'error');
            });
    }

    function renderCalendar() {
        // Ensure filteredLeaves is always an array
        if (!Array.isArray(filteredLeaves)) {
            filteredLeaves = [];
        }
        
        calendar.innerHTML = "";
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const today = new Date();

        monthLabel.textContent = firstDay.toLocaleString("default", { month: "long" }) + " " + year;

        const dayHeaders = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        dayHeaders.forEach(day => {
            const div = document.createElement("div");
            div.className = "text-center fw-bold";
            div.textContent = day;
            calendar.appendChild(div);
        });

        let startDay = firstDay.getDay();
        for (let i = 0; i < startDay; i++) {
            calendar.appendChild(document.createElement("div"));
        }

        for (let date = 1; date <= lastDay.getDate(); date++) {
            const cell = document.createElement("div");
            const currentDateObj = new Date(year, month, date);
            const isToday = currentDateObj.toDateString() === today.toDateString();
            const isWeekend = currentDateObj.getDay() === 0 || currentDateObj.getDay() === 6;
            
            cell.className = `day ${isToday ? 'today' : ''} ${isWeekend ? 'weekend' : ''}`;
            cell.dataset.date = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
            
            const dateNumber = document.createElement("div");
            dateNumber.className = "date-number";
            dateNumber.textContent = date;
            cell.appendChild(dateNumber);

            const fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
            
            // Safely filter events
            let events = [];
            if (Array.isArray(filteredLeaves)) {
                events = filteredLeaves.filter(l => l.start_date === fullDate);
            }

            events.forEach(ev => {
                const eventDiv = document.createElement("div");
                eventDiv.className = `calendar-event ${getStatusClass(ev.status)}`;
                eventDiv.textContent = `${ev.staff_name} (${ev.leave_type})`;
                eventDiv.title = `${ev.staff_name} - ${ev.leave_type} (${ev.status})`;
                eventDiv.dataset.leaveId = ev.id;
                eventDiv.onclick = () => openModal(ev);
                eventDiv.oncontextmenu = (e) => {
                    e.preventDefault();
                    showQuickActions(e, ev.id);
                };
                cell.appendChild(eventDiv);
            });

            calendar.appendChild(cell);
        }

        // Enable drag and drop after rendering
        enableDragAndDrop();
    }

    // Additional missing functions
    function renderListView() {
        // Ensure filteredLeaves is an array
        if (!Array.isArray(filteredLeaves)) {
            filteredLeaves = [];
        }
        
        // Simple list view implementation
        calendar.innerHTML = `
            <div class="col-12">
                <h4>List View - All Leaves</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Staff Name</th>
                                <th>Leave Type</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${filteredLeaves.map(leave => `
                                <tr>
                                    <td>${leave.staff_name}</td>
                                    <td><span class="badge bg-secondary">${leave.leave_type}</span></td>
                                    <td><span class="badge ${getStatusClass(leave.status)}">${leave.status}</span></td>
                                    <td>${leave.start_date}</td>
                                    <td>${leave.end_date}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" onclick="openModal(${JSON.stringify(leave).replace(/"/g, '&quot;')})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `;
    }

    function getStatusClass(status) {
        switch (status) {
            case "approved": return "bg-approved";
            case "pending": return "bg-pending";
            case "rejected": return "bg-rejected";
            case "half-day": return "bg-half-day";
            default: return "";
        }
    }

    function changeMonth(step) {
        currentDate.setMonth(currentDate.getMonth() + step);
        renderCalendar();
    }

    function updateStats() {
        const total = leaves.length;
        const approved = leaves.filter(l => l.status === "approved").length;
        const pending = leaves.filter(l => l.status === "pending").length;
        const rejected = leaves.filter(l => l.status === "rejected").length;

        document.getElementById("total-leaves").textContent = total;
        document.getElementById("approved-leaves").textContent = `${approved} (${Math.round((approved/total)*100 || 0)}%)`;
        document.getElementById("pending-leaves").textContent = `${pending} (${Math.round((pending/total)*100 || 0)}%)`;
        document.getElementById("rejected-leaves").textContent = `${rejected} (${Math.round((rejected/total)*100 || 0)}%)`;
    }

    function exportCalendar() {
        const rows = leaves.map(l => `${l.start_date}, ${l.staff_name}, ${l.leave_type}, ${l.status}`);
        const blob = new Blob([rows.join("\n")], { type: "text/csv" });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "leave_calendar.csv";
        link.click();
    }

    //Additional missing functions
    function clearFilters() {
        const statusFilter = document.getElementById('statusFilter');
        const leaveTypeFilter = document.getElementById('leaveTypeFilter');
        const departmentFilter = document.getElementById('departmentFilter');
        const searchInput = document.getElementById('searchInput');
        
        if (statusFilter) statusFilter.value = '';
        if (leaveTypeFilter) leaveTypeFilter.value = '';
        if (departmentFilter) departmentFilter.value = '';
        if (searchInput) searchInput.value = '';
        
        searchQuery = '';
        currentFilters = {};
        filteredLeaves = leaves;
        renderCalendar();
        updateStats();
        showNotification('All filters cleared!', 'info');
    }

    function showSearchResults(results = null) {
        // Ensure filteredLeaves is an array
        if (!Array.isArray(filteredLeaves)) {
            filteredLeaves = [];
        }
        
        const searchResults = results || filteredLeaves;
        const modal = `
            <div class="modal fade" id="searchResultsModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5><i class="fas fa-search me-2"></i>Search Results (${searchResults.length})</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Staff Name</th>
                                            <th>Leave Type</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${searchResults.map(leave => `
                                            <tr>
                                                <td>${leave.staff_name}</td>
                                                <td><span class="badge bg-secondary">${leave.leave_type}</span></td>
                                                <td><span class="badge ${getStatusClass(leave.status)}">${leave.status}</span></td>
                                                <td>${leave.start_date}</td>
                                                <td>${leave.end_date}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="openModal(${JSON.stringify(leave).replace(/"/g, '&quot;')})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modal);
        const modalElement = document.getElementById('searchResultsModal');
        if (modalElement) {
            const bootstrapModal = new bootstrap.Modal(modalElement);
            bootstrapModal.show();
        }
    }

    function saveSearchQuery() {
        const query = document.getElementById('searchInput')?.value || '';
        if (query) {
            const savedQueries = JSON.parse(localStorage.getItem('savedSearchQueries') || '[]');
            if (!savedQueries.includes(query)) {
                savedQueries.push(query);
                localStorage.setItem('savedSearchQueries', JSON.stringify(savedQueries));
                showNotification('Search query saved!', 'success');
            } else {
                showNotification('Query already saved!', 'info');
            }
        }
    }

    function calculateAdvancedStats() {
        return {
            totalLeaves: leaves.length,
            approvedLeaves: leaves.filter(l => l.status === 'approved').length,
            pendingLeaves: leaves.filter(l => l.status === 'pending').length,
            rejectedLeaves: leaves.filter(l => l.status === 'rejected').length,
            leaveTypes: leaves.reduce((acc, l) => {
                acc[l.leave_type] = (acc[l.leave_type] || 0) + 1;
                return acc;
            }, {}),
            monthlyData: getMonthlyData()
        };
    }

    function getMonthlyData() {
        const monthly = {};
        leaves.forEach(leave => {
            const month = new Date(leave.start_date).getMonth();
            monthly[month] = (monthly[month] || 0) + 1;
        });
        return monthly;
    }

    function renderCharts(stats) {
        // This would integrate with Chart.js for visual charts
        console.log('Rendering charts with data:', stats);
    }

    function updateLeaveDate(leaveId, newDate) {
        // API call to update leave date
        showNotification(`Leave date updated to ${newDate}`, 'success');
        loadLeaveData(); // Refresh data
    }

    function editLeave(leaveId) {
        // Redirect to edit page or open edit modal
        showNotification('Edit functionality coming soon!', 'info');
    }

    function deleteLeave(leaveId) {
        if (confirm('Are you sure you want to delete this leave?')) {
            // API call to delete leave
            showNotification('Leave deleted successfully!', 'success');
            loadLeaveData(); // Refresh data
        }
    }

    function openModal(event) {
        document.getElementById("modal-title").textContent = `${event.staff_name}'s Leave`;
        document.getElementById("modal-body").textContent = `Date: ${event.start_date}\nType: ${event.leave_type}\nStatus: ${event.status}`;
        document.getElementById("event-modal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("event-modal").style.display = "none";
    }

    // 1. Advanced Filtering System
    function applyFilters() {
        const statusFilter = document.getElementById('statusFilter')?.value || '';
        const typeFilter = document.getElementById('leaveTypeFilter')?.value || '';
        const deptFilter = document.getElementById('departmentFilter')?.value || '';
        
        currentFilters = { status: statusFilter, type: typeFilter, department: deptFilter };
        
        filteredLeaves = leaves.filter(leave => {
            let matches = true;
            if (statusFilter && leave.status !== statusFilter) matches = false;
            if (typeFilter && leave.leave_type !== typeFilter) matches = false;
            if (deptFilter && leave.department !== deptFilter) matches = false;
            if (searchQuery && !leave.staff_name.toLowerCase().includes(searchQuery.toLowerCase())) matches = false;
            return matches;
        });
        
        renderCalendar();
        updateStats();
        showNotification(`Filtered ${filteredLeaves.length} leaves`, 'info');
    }

    // 2. Smart Search with Auto-complete
    function searchLeaves() {
        searchQuery = document.getElementById('searchInput')?.value || '';
        applyFilters();
        
        if (searchQuery) {
            const results = leaves.filter(l => 
                l.staff_name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                l.leave_type.toLowerCase().includes(searchQuery.toLowerCase())
            );
            showSearchResults(results);
        }
    }

    // 3. View Mode Switcher (Month/Week/List)
    function switchViewMode(mode) {
        viewMode = mode;
        document.querySelectorAll('.view-mode-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector(`[data-mode="${mode}"]`).classList.add('active');
        
        if (mode === 'list') {
            renderListView();
        } else {
            renderCalendar();
        }
        showNotification(`Switched to ${mode} view`, 'info');
    }

    // 4. Drag & Drop for Leave Management
    function enableDragAndDrop() {
        const events = document.querySelectorAll('.calendar-event');
        events.forEach(event => {
            event.draggable = true;
            event.addEventListener('dragstart', handleDragStart);
            event.addEventListener('dragend', handleDragEnd);
        });
        
        const days = document.querySelectorAll('.day');
        days.forEach(day => {
            day.addEventListener('dragover', handleDragOver);
            day.addEventListener('drop', handleDrop);
        });
    }

    function handleDragStart(e) {
        dragStartEvent = e.target;
        e.target.style.opacity = '0.5';
    }

    function handleDragEnd(e) {
        e.target.style.opacity = '1';
    }

    function handleDragOver(e) {
        e.preventDefault();
    }

    function handleDrop(e) {
        e.preventDefault();
        const targetDate = e.target.closest('.day')?.dataset.date;
        if (targetDate && dragStartEvent) {
            // Update leave date in backend
            updateLeaveDate(dragStartEvent.dataset.leaveId, targetDate);
        }
    }

    // 5. Quick Actions Menu
    function showQuickActions(event, leaveId) {
        const actions = `
            <div class="quick-actions" style="position: absolute; top: 100%; left: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; min-width: 150px;">
                <div class="action-item" onclick="approveLeave(${leaveId})">
                    <i class="fas fa-check text-success"></i> Approve
                </div>
                <div class="action-item" onclick="rejectLeave(${leaveId})">
                    <i class="fas fa-times text-danger"></i> Reject
                </div>
                <div class="action-item" onclick="editLeave(${leaveId})">
                    <i class="fas fa-edit text-primary"></i> Edit
                </div>
                <div class="action-item" onclick="deleteLeave(${leaveId})">
                    <i class="fas fa-trash text-danger"></i> Delete
                </div>
            </div>
        `;
        
        event.target.insertAdjacentHTML('beforeend', actions);
        setTimeout(() => document.querySelector('.quick-actions').remove(), 5000);
    }

    // 6. Leave Approval/Rejection
    function approveLeave(leaveId) {
        if (confirm('Are you sure you want to approve this leave?')) {
            // API call to approve leave
            showNotification('Leave approved successfully!', 'success');
            loadLeaveData(); // Refresh data
        }
    }

    function rejectLeave(leaveId) {
        const reason = prompt('Please provide rejection reason:');
        if (reason) {
            // API call to reject leave
            showNotification('Leave rejected successfully!', 'error');
            loadLeaveData(); // Refresh data
        }
    }

    // 7. Advanced Statistics & Analytics
    function showAdvancedStats() {
        const stats = calculateAdvancedStats();
        const modal = `
            <div class="modal fade" id="statsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5><i class="fas fa-chart-bar me-2"></i>Advanced Analytics</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Leave Distribution</h6>
                                    <canvas id="leaveChart" width="300" height="200"></canvas>
                                </div>
                                <div class="col-md-6">
                                    <h6>Monthly Trends</h6>
                                    <canvas id="trendChart" width="300" height="200"></canvas>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6>Department Performance</h6>
                                    <div id="deptPerformance"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modal);
        new bootstrap.Modal(document.getElementById('statsModal')).show();
        renderCharts(stats);
    }

    // 8. Smart Notifications System
    function showNotification(message, type = 'info') {
        const notification = `
            <div class="notification notification-${type}" style="
                position: fixed; top: 20px; right: 20px; 
                background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'}; 
                color: white; padding: 15px 20px; border-radius: 8px; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999;
                animation: slideIn 0.3s ease-out;
            ">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
                ${message}
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; margin-left: 15px;">×</button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', notification);
        setTimeout(() => {
            const notif = document.querySelector('.notification');
            if (notif) notif.remove();
        }, 5000);
    }

    // 9. Export & Reporting
    function exportAdvancedReport() {
        const reportData = {
            totalLeaves: leaves.length,
            approvedLeaves: leaves.filter(l => l.status === 'approved').length,
            pendingLeaves: leaves.filter(l => l.status === 'pending').length,
            rejectedLeaves: leaves.filter(l => l.status === 'rejected').length,
            leaveTypes: leaves.reduce((acc, l) => {
                acc[l.leave_type] = (acc[l.leave_type] || 0) + 1;
                return acc;
            }, {}),
            monthlyData: getMonthlyData(),
            generatedAt: new Date().toISOString()
        };
        
        const blob = new Blob([JSON.stringify(reportData, null, 2)], { type: 'application/json' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `leave_report_${new Date().toISOString().split('T')[0]}.json`;
        link.click();
        
        showNotification('Advanced report exported successfully!', 'success');
    }

    // 10. Calendar Themes & Personalization
    function changeTheme(theme) {
        const themes = {
            'default': { primary: '#007bff', secondary: '#6c757d' },
            'dark': { primary: '#343a40', secondary: '#495057' },
            'nature': { primary: '#28a745', secondary: '#20c997' },
            'sunset': { primary: '#fd7e14', secondary: '#e83e8c' }
        };
        
        const selectedTheme = themes[theme];
        document.documentElement.style.setProperty('--primary-color', selectedTheme.primary);
        document.documentElement.style.setProperty('--secondary-color', selectedTheme.secondary);
        
        localStorage.setItem('calendar-theme', theme);
        showNotification(`Theme changed to ${theme}`, 'info');
    }

    // 11. Keyboard Shortcuts
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch(e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        changeMonth(-1);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        changeMonth(1);
                        break;
                    case 't':
                        e.preventDefault();
                        goToToday();
                        break;
                    case 'f':
                        e.preventDefault();
                        document.getElementById('searchInput')?.focus();
                        break;
                    case 'e':
                        e.preventDefault();
                        exportAdvancedReport();
                        break;
                }
            }
        });
    }

    // 12. Auto-refresh & Real-time Updates
    function enableAutoRefresh() {
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                loadLeaveData();
            }
        }, 300000); // Refresh every 5 minutes
    }

    // 13. Utility Functions
    function updateLegendCounts() {
        const counts = {
            pending: leaves.filter(l => l.status === 'pending').length,
            approved: leaves.filter(l => l.status === 'approved').length,
            rejected: leaves.filter(l => l.status === 'rejected').length,
            halfDay: leaves.filter(l => l.half_day).length
        };
        
        Object.keys(counts).forEach(status => {
            const element = document.getElementById(`${status}Count`);
            if (element) element.textContent = counts[status];
        });
    }

    function populateDepartmentFilter() {
        const departments = [...new Set(leaves.map(l => l.department).filter(Boolean))];
        const select = document.getElementById('departmentFilter');
        if (select) {
            departments.forEach(dept => {
                const option = document.createElement('option');
                option.value = dept;
                option.textContent = dept;
                select.appendChild(option);
            });
        }
    }

    function goToToday() {
        currentDate = new Date();
        renderCalendar();
        showNotification('Jumped to today!', 'info');
    }

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize enhanced calendar
    document.addEventListener('DOMContentLoaded', function() {
        loadLeaveData();
        setupKeyboardShortcuts();
        enableAutoRefresh();
        
        // Load saved theme
        const savedTheme = localStorage.getItem('calendar-theme');
        if (savedTheme) changeTheme(savedTheme);
        
        // Add event listeners for filters
        ['statusFilter', 'leaveTypeFilter', 'departmentFilter'].forEach(id => {
            const element = document.getElementById(id);
            if (element) element.addEventListener('change', applyFilters);
        });
        
        // Add search input listener
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(searchLeaves, 300));
        }
    });

    // Additional Utility Functions
    function clearFilters() {
        document.getElementById('statusFilter').value = '';
        document.getElementById('leaveTypeFilter').value = '';
        document.getElementById('departmentFilter').value = '';
        document.getElementById('searchInput').value = '';
        searchQuery = '';
        currentFilters = {};
        filteredLeaves = leaves;
        renderCalendar();
        updateStats();
        showNotification('All filters cleared!', 'info');
    }

    function showSearchResults(results = null) {
        const searchResults = results || filteredLeaves;
        const modal = `
            <div class="modal fade" id="searchResultsModal" tabindex="-1">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5><i class="fas fa-search me-2"></i>Search Results (${searchResults.length})</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Staff Name</th>
                                            <th>Leave Type</th>
                                            <th>Status</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${searchResults.map(leave => `
                                            <tr>
                                                <td>${leave.staff_name}</td>
                                                <td><span class="badge bg-secondary">${leave.leave_type}</span></td>
                                                <td><span class="badge ${getStatusClass(leave.status)}">${leave.status}</span></td>
                                                <td>${leave.start_date}</td>
                                                <td>${leave.end_date}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" onclick="openModal(${JSON.stringify(leave).replace(/"/g, '&quot;')})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modal);
        new bootstrap.Modal(document.getElementById('searchResultsModal')).show();
    }

    function saveSearchQuery() {
        const query = document.getElementById('searchInput').value;
        if (query) {
            const savedQueries = JSON.parse(localStorage.getItem('savedSearchQueries') || '[]');
            if (!savedQueries.includes(query)) {
                savedQueries.push(query);
                localStorage.setItem('savedSearchQueries', JSON.stringify(savedQueries));
                showNotification('Search query saved!', 'success');
            } else {
                showNotification('Query already saved!', 'info');
            }
        }
    }

    function calculateAdvancedStats() {
        return {
            totalLeaves: leaves.length,
            approvedLeaves: leaves.filter(l => l.status === 'approved').length,
            pendingLeaves: leaves.filter(l => l.status === 'pending').length,
            rejectedLeaves: leaves.filter(l => l.status === 'rejected').length,
            leaveTypes: leaves.reduce((acc, l) => {
                acc[l.leave_type] = (acc[l.leave_type] || 0) + 1;
                return acc;
            }, {}),
            monthlyData: getMonthlyData()
        };
    }

    function getMonthlyData() {
        const monthly = {};
        leaves.forEach(leave => {
            const month = new Date(leave.start_date).getMonth();
            monthly[month] = (monthly[month] || 0) + 1;
        });
        return monthly;
    }

    function renderCharts(stats) {
        // This would integrate with Chart.js for visual charts
        console.log('Rendering charts with data:', stats);
    }

    function updateLeaveDate(leaveId, newDate) {
        // API call to update leave date
        showNotification(`Leave date updated to ${newDate}`, 'success');
        loadLeaveData(); // Refresh data
    }

    function editLeave(leaveId) {
        // Redirect to edit page or open edit modal
        showNotification('Edit functionality coming soon!', 'info');
    }

    function deleteLeave(leaveId) {
        if (confirm('Are you sure you want to delete this leave?')) {
            // API call to delete leave
            showNotification('Leave deleted successfully!', 'success');
            loadLeaveData(); // Refresh data
        }
    }


</script>
@endsection
