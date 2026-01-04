@extends('superadmin.app')

@section('title', 'Support & Communication')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-secondary text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-headset me-3"></i>Support & Communication
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Manage support tickets, announcements, and communication logs</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">Support Center</div>
                            <small class="opacity-75">24/7 Available</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-primary fw-bold small mb-1">Open Tickets</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="openTicketsCount">0</div>
                            <div class="text-primary small">
                                <i class="fas fa-ticket-alt me-1"></i>Needs attention
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-ticket-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-success fw-bold small mb-1">Resolved Today</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="resolvedTodayCount">0</div>
                            <div class="text-success small">
                                <i class="fas fa-check-circle me-1"></i>Great work!
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-warning fw-bold small mb-1">Avg Response</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="avgResponseTime">0m</div>
                            <div class="text-warning small">
                                <i class="fas fa-clock me-1"></i>Response time
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-info fw-bold small mb-1">Announcements</div>
                            <div class="h2 mb-0 fw-bold text-dark" id="activeAnnouncementsCount">0</div>
                            <div class="text-info small">
                                <i class="fas fa-bullhorn me-1"></i>Active posts
                            </div>
                        </div>
                        <div class="ms-3">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-bullhorn fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-0">
                    <ul class="nav nav-tabs nav-fill" id="supportTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tickets-tab" data-bs-toggle="tab" data-bs-target="#tickets" type="button" role="tab">
                                <i class="fas fa-ticket-alt me-2"></i>Support Tickets
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab">
                                <i class="fas fa-bullhorn me-2"></i>Announcements
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="communication-tab" data-bs-toggle="tab" data-bs-target="#communication" type="button" role="tab">
                                <i class="fas fa-comments me-2"></i>Communication Logs
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="knowledge-tab" data-bs-toggle="tab" data-bs-target="#knowledge" type="button" role="tab">
                                <i class="fas fa-book me-2"></i>Knowledge Base
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="supportTabsContent">
                        <!-- Support Tickets Tab -->
                        <div class="tab-pane fade show active" id="tickets" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="mb-1 fw-bold">Support Tickets</h5>
                                    <p class="text-muted mb-0">Manage and respond to support requests</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="refreshTickets()">
                                        <i class="fas fa-sync-alt me-1"></i>Refresh
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="createTicket()">
                                        <i class="fas fa-plus me-1"></i>New Ticket
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ticket #</th>
                                            <th>Title</th>
                                            <th>School</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="ticketsTable">
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Announcements Tab -->
                        <div class="tab-pane fade" id="announcements" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="mb-1 fw-bold">Announcements</h5>
                                    <p class="text-muted mb-0">Create and manage system announcements</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="refreshAnnouncements()">
                                        <i class="fas fa-sync-alt me-1"></i>Refresh
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="createAnnouncement()">
                                        <i class="fas fa-plus me-1"></i>New Announcement
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row" id="announcementsGrid">
                                <!-- Data will be loaded here -->
                            </div>
                        </div>
                        
                        <!-- Communication Logs Tab -->
                        <div class="tab-pane fade" id="communication" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="mb-1 fw-bold">Communication Logs</h5>
                                    <p class="text-muted mb-0">Track all communication activities</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="refreshCommunicationLogs()">
                                        <i class="fas fa-sync-alt me-1"></i>Refresh
                                    </button>
                                    <button class="btn btn-outline-success btn-sm" onclick="exportCommunicationLogs()">
                                        <i class="fas fa-download me-1"></i>Export
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Type</th>
                                            <th>From</th>
                                            <th>To</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="communicationLogsTable">
                                        <!-- Data will be loaded here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Knowledge Base Tab -->
                        <div class="tab-pane fade" id="knowledge" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="mb-1 fw-bold">Knowledge Base</h5>
                                    <p class="text-muted mb-0">Manage help articles and documentation</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm" onclick="refreshKnowledgeBase()">
                                        <i class="fas fa-sync-alt me-1"></i>Refresh
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="createArticle()">
                                        <i class="fas fa-plus me-1"></i>New Article
                                    </button>
                                </div>
                            </div>
                            
                            <div class="row" id="knowledgeBaseGrid">
                                <!-- Data will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    loadTickets();
    loadAnnouncements();
    loadCommunicationLogs();
    loadKnowledgeBase();
    updateStats();
    
    // Auto-refresh every 30 seconds
    setInterval(function() {
        updateStats();
        loadTickets();
        loadAnnouncements();
        loadCommunicationLogs();
        loadKnowledgeBase();
    }, 30000);
});

function updateStats() {
    // Simulate real-time stats
    $('#openTicketsCount').text(Math.floor(Math.random() * 20) + 5);
    $('#resolvedTodayCount').text(Math.floor(Math.random() * 15) + 3);
    $('#avgResponseTime').text(Math.floor(Math.random() * 60) + 15 + 'm');
    $('#activeAnnouncementsCount').text(Math.floor(Math.random() * 10) + 2);
}

function loadTickets() {
    const tickets = [
        {
            id: 'TICKET-001',
            title: 'Login issues with admin panel',
            school: 'ABC School',
            priority: 'high',
            status: 'open',
            created: '2 hours ago'
        },
        {
            id: 'TICKET-002',
            title: 'Payment gateway not working',
            school: 'XYZ Academy',
            priority: 'urgent',
            status: 'in_progress',
            created: '4 hours ago'
        },
        {
            id: 'TICKET-003',
            title: 'Student data export problem',
            school: 'DEF College',
            priority: 'medium',
            status: 'resolved',
            created: '1 day ago'
        },
        {
            id: 'TICKET-004',
            title: 'Report generation slow',
            school: 'ABC School',
            priority: 'low',
            status: 'open',
            created: '2 days ago'
        }
    ];
    
    let tableHtml = '';
    tickets.forEach(ticket => {
        const priorityClass = {
            'urgent': 'danger',
            'high': 'warning',
            'medium': 'info',
            'low': 'secondary'
        }[ticket.priority];
        
        const statusClass = {
            'open': 'primary',
            'in_progress': 'warning',
            'resolved': 'success',
            'closed': 'secondary'
        }[ticket.status];
        
        tableHtml += `
            <tr>
                <td><code>${ticket.id}</code></td>
                <td>${ticket.title}</td>
                <td>${ticket.school}</td>
                <td><span class="badge bg-${priorityClass}">${ticket.priority.toUpperCase()}</span></td>
                <td><span class="badge bg-${statusClass}">${ticket.status.replace('_', ' ').toUpperCase()}</span></td>
                <td>${ticket.created}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewTicket('${ticket.id}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-success" onclick="replyTicket('${ticket.id}')">
                            <i class="fas fa-reply"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="updateTicketStatus('${ticket.id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    $('#ticketsTable').html(tableHtml);
}

function loadAnnouncements() {
    const announcements = [
        {
            id: 1,
            title: 'System Maintenance Scheduled',
            content: 'We will be performing scheduled maintenance on Sunday from 2 AM to 4 AM.',
            created: '2 hours ago',
            status: 'active'
        },
        {
            id: 2,
            title: 'New Features Available',
            content: 'Check out the new AI-powered analytics dashboard and reporting tools.',
            created: '1 day ago',
            status: 'active'
        },
        {
            id: 3,
            title: 'Payment Gateway Update',
            content: 'We have updated our payment processing system for better security.',
            created: '3 days ago',
            status: 'expired'
        }
    ];
    
    let gridHtml = '';
    announcements.forEach(announcement => {
        const statusClass = announcement.status === 'active' ? 'success' : 'secondary';
        
        gridHtml += `
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1 fw-bold">${announcement.title}</h6>
                            <span class="badge bg-${statusClass}">${announcement.status.toUpperCase()}</span>
                        </div>
                        <p class="text-muted small mb-2">${announcement.content}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">${announcement.created}</small>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="editAnnouncement(${announcement.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteAnnouncement(${announcement.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#announcementsGrid').html(gridHtml);
}

function loadCommunicationLogs() {
    const logs = [
        {
            type: 'email',
            from: 'support@system.com',
            to: 'admin@school.com',
            subject: 'Welcome to the system',
            status: 'sent',
            time: '2 hours ago'
        },
        {
            type: 'sms',
            from: 'System',
            to: '+1234567890',
            subject: 'Login notification',
            status: 'delivered',
            time: '4 hours ago'
        },
        {
            type: 'chat',
            from: 'Support Agent',
            to: 'User',
            subject: 'Technical support chat',
            status: 'completed',
            time: '1 day ago'
        }
    ];
    
    let tableHtml = '';
    logs.forEach(log => {
        const typeClass = {
            'email': 'primary',
            'sms': 'success',
            'chat': 'info',
            'announcement': 'warning'
        }[log.type];
        
        const statusClass = {
            'sent': 'success',
            'delivered': 'success',
            'failed': 'danger',
            'completed': 'info'
        }[log.status];
        
        tableHtml += `
            <tr>
                <td><span class="badge bg-${typeClass}">${log.type.toUpperCase()}</span></td>
                <td>${log.from}</td>
                <td>${log.to}</td>
                <td>${log.subject}</td>
                <td><span class="badge bg-${statusClass}">${log.status.toUpperCase()}</span></td>
                <td>${log.time}</td>
            </tr>
        `;
    });
    
    $('#communicationLogsTable').html(tableHtml);
}

function loadKnowledgeBase() {
    const articles = [
        {
            id: 1,
            title: 'How to create a new school',
            category: 'Getting Started',
            views: 150,
            status: 'published'
        },
        {
            id: 2,
            title: 'Payment gateway setup guide',
            category: 'Payments',
            views: 89,
            status: 'published'
        },
        {
            id: 3,
            title: 'User role management',
            category: 'Administration',
            views: 67,
            status: 'draft'
        }
    ];
    
    let gridHtml = '';
    articles.forEach(article => {
        const statusClass = article.status === 'published' ? 'success' : 'warning';
        
        gridHtml += `
            <div class="col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1 fw-bold">${article.title}</h6>
                            <span class="badge bg-${statusClass}">${article.status.toUpperCase()}</span>
                        </div>
                        <p class="text-muted small mb-2">Category: ${article.category}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-eye me-1"></i>${article.views} views</small>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-outline-primary" onclick="viewArticle(${article.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" onclick="editArticle(${article.id})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteArticle(${article.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });
    
    $('#knowledgeBaseGrid').html(gridHtml);
}

// Action functions
function refreshTickets() {
    loadTickets();
    showAlert('success', 'Tickets refreshed!');
}

function refreshAnnouncements() {
    loadAnnouncements();
    showAlert('success', 'Announcements refreshed!');
}

function refreshCommunicationLogs() {
    loadCommunicationLogs();
    showAlert('success', 'Communication logs refreshed!');
}

function refreshKnowledgeBase() {
    loadKnowledgeBase();
    showAlert('success', 'Knowledge base refreshed!');
}

function createTicket() {
    showAlert('info', 'Creating new ticket...');
}

function createAnnouncement() {
    showAlert('info', 'Creating new announcement...');
}

function createArticle() {
    showAlert('info', 'Creating new article...');
}

function viewTicket(id) {
    showAlert('info', `Viewing ticket ${id}`);
}

function replyTicket(id) {
    showAlert('info', `Replying to ticket ${id}`);
}

function updateTicketStatus(id) {
    showAlert('info', `Updating status for ticket ${id}`);
}

function editAnnouncement(id) {
    showAlert('info', `Editing announcement ${id}`);
}

function deleteAnnouncement(id) {
    showAlert('warning', `Deleting announcement ${id}`);
}

function exportCommunicationLogs() {
    showAlert('info', 'Exporting communication logs...');
}

function viewArticle(id) {
    showAlert('info', `Viewing article ${id}`);
}

function editArticle(id) {
    showAlert('info', `Editing article ${id}`);
}

function deleteArticle(id) {
    showAlert('warning', `Deleting article ${id}`);
}

function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('body').prepend(alertHtml);
    
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 3000);
}
</script>
@endsection
