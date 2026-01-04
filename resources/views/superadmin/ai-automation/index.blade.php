@extends('superadmin.app')

@section('title', 'AI & Automation Tools')

@section('content')
<div class="container-fluid p-0">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg bg-gradient-primary text-white overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-2 fw-bold">
                                <i class="fas fa-robot me-3"></i>AI & Automation Tools
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">Intelligent automation and AI-powered analytics</p>
                        </div>
                        <div class="text-end">
                            <div class="h4 mb-0">AI Assistant</div>
                            <small class="opacity-75">Always Learning</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Tools Grid -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-primary fw-bold small mb-1">AI Report Generator</div>
                            <div class="h5 mb-2 fw-bold text-dark">Natural Language Reports</div>
                            <p class="text-muted mb-3">Generate reports using natural language queries</p>
                            <a href="{{ route('superadmin.ai-automation.report-generator') }}" class="btn btn-primary">
                                <i class="fas fa-chart-line me-2"></i>Generate Report
                            </a>
                        </div>
                        <div class="ms-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-file-alt fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-success fw-bold small mb-1">AI Chatbot</div>
                            <div class="h5 mb-2 fw-bold text-dark">Smart Assistant</div>
                            <p class="text-muted mb-3">Ask questions and get intelligent answers</p>
                            <a href="{{ route('superadmin.ai-automation.chatbot') }}" class="btn btn-success">
                                <i class="fas fa-comments me-2"></i>Start Chat
                            </a>
                        </div>
                        <div class="ms-3">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-robot fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-0 shadow-lg h-100 overflow-hidden position-relative">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="text-uppercase text-warning fw-bold small mb-1">Fraud Detection</div>
                            <div class="h5 mb-2 fw-bold text-dark">Security Monitoring</div>
                            <p class="text-muted mb-3">Detect suspicious activities and patterns</p>
                            <a href="{{ route('superadmin.ai-automation.fraud-detection') }}" class="btn btn-warning">
                                <i class="fas fa-shield-alt me-2"></i>View Alerts
                            </a>
                        </div>
                        <div class="ms-3">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-shield-alt fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">
                        <i class="fas fa-brain me-2 text-primary"></i>AI Insights & Predictions
                    </h5>
                    <p class="text-muted mb-0">Smart analytics powered by artificial intelligence</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="ai-insight-card p-4 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-chart-line text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Revenue Forecast</h6>
                                        <p class="mb-1 small text-muted">Next quarter predicted revenue</p>
                                        <div class="h4 mb-0 text-primary">₹2.4M</div>
                                        <span class="badge bg-success">+15% growth</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="ai-insight-card p-4 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-arrow-up text-warning me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Upgrade Suggestions</h6>
                                        <p class="mb-1 small text-muted">Schools ready for plan upgrade</p>
                                        <div class="h4 mb-0 text-warning">3</div>
                                        <span class="badge bg-warning">High potential</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="ai-insight-card p-4 bg-light rounded">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-exclamation-circle text-danger me-3 mt-1"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Churn Alert</h6>
                                        <p class="mb-1 small text-muted">Schools showing low activity</p>
                                        <div class="h4 mb-0 text-danger">2</div>
                                        <span class="badge bg-danger">Action needed</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-white border-0 p-4">
                    <h5 class="mb-1 fw-bold">Quick AI Actions</h5>
                    <p class="text-muted mb-0">Common AI-powered tasks and queries</p>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <button class="btn btn-outline-primary w-100 p-3 text-start" onclick="askAI('Show me schools whose plan will expire in 10 days')">
                                <i class="fas fa-calendar-exclamation me-2"></i>
                                <div>
                                    <div class="fw-bold">Expiring Plans</div>
                                    <small class="text-muted">Find schools with expiring subscriptions</small>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-success w-100 p-3 text-start" onclick="askAI('What is the revenue trend for last quarter?')">
                                <i class="fas fa-chart-line me-2"></i>
                                <div>
                                    <div class="fw-bold">Revenue Analysis</div>
                                    <small class="text-muted">Get detailed revenue insights</small>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-warning w-100 p-3 text-start" onclick="askAI('Which schools have the highest user activity?')">
                                <i class="fas fa-users me-2"></i>
                                <div>
                                    <div class="fw-bold">User Activity</div>
                                    <small class="text-muted">Find most active schools</small>
                                </div>
                            </button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-outline-info w-100 p-3 text-start" onclick="askAI('Show me plan upgrade recommendations')">
                                <i class="fas fa-arrow-up me-2"></i>
                                <div>
                                    <div class="fw-bold">Upgrade Suggestions</div>
                                    <small class="text-muted">Get AI-powered recommendations</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI Query Modal -->
<div class="modal fade" id="aiQueryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-robot me-2 text-primary"></i>AI Assistant Response
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="aiResponseContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">AI is thinking...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function askAI(query) {
    $('#aiQueryModal').modal('show');
    
    $.ajax({
        url: '{{ route("superadmin.dashboard.ai-query") }}',
        method: 'POST',
        data: {
            query: query,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            let content = '';
            
            if (response.type === 'expiring_schools') {
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>${response.message}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>Plan</th>
                                    <th>Expires</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${response.data.map(school => `
                                    <tr>
                                        <td>${school.name}</td>
                                        <td>${school.payment_plans[0]?.name || 'N/A'}</td>
                                        <td>${school.payment_plans[0]?.expires_at || 'N/A'}</td>
                                        <td><span class="badge bg-warning">Expiring Soon</span></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else if (response.type === 'revenue_trend') {
                content = `
                    <div class="alert alert-success">
                        <h6><i class="fas fa-chart-line me-2"></i>${response.message}</h6>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-primary">₹${response.data.current_quarter.toLocaleString()}</h5>
                                <small>Current Quarter</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-info">₹${response.data.previous_quarter.toLocaleString()}</h5>
                                <small>Previous Quarter</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 bg-light rounded">
                                <h5 class="text-success">${response.data.growth}%</h5>
                                <small>Growth Rate</small>
                            </div>
                        </div>
                    </div>
                `;
            } else if (response.type === 'active_schools') {
                content = `
                    <div class="alert alert-info">
                        <h6><i class="fas fa-users me-2"></i>${response.message}</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>School Name</th>
                                    <th>Active Users</th>
                                    <th>Activity Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${response.data.map(school => `
                                    <tr>
                                        <td>${school.name}</td>
                                        <td>${school.users_count}</td>
                                        <td>
                                            <span class="badge ${school.users_count > 50 ? 'bg-success' : school.users_count > 20 ? 'bg-warning' : 'bg-danger'}">
                                                ${school.users_count > 50 ? 'High' : school.users_count > 20 ? 'Medium' : 'Low'}
                                            </span>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else {
                content = `
                    <div class="alert alert-primary">
                        <h6><i class="fas fa-robot me-2"></i>AI Assistant</h6>
                        <p class="mb-0">${response.message}</p>
                    </div>
                `;
            }
            
            $('#aiResponseContent').html(content);
        },
        error: function() {
            $('#aiResponseContent').html(`
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Error</h6>
                    <p class="mb-0">Sorry, I encountered an error processing your request. Please try again.</p>
                </div>
            `);
        }
    });
}
</script>
@endsection
