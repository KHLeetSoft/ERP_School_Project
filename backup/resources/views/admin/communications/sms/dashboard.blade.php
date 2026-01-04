@extends('admin.layout.app')

@section('title', 'SMS Dashboard')

@push('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('styles')
<style>
.stats-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-card.success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stats-card.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-card.info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-card.danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stats-number {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stats-label {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
}

.recent-messages {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.message-item {
    padding: 1rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.3s ease;
}

.message-item:hover {
    background-color: #f8f9fa;
}

.message-item:last-child {
    border-bottom: none;
}

.gateway-stats {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.gateway-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #f1f1f1;
    transition: background-color 0.3s ease;
}

.gateway-item:hover {
    background-color: #f8f9fa;
}

.gateway-item:last-child {
    border-bottom: none;
}

.progress-bar {
    height: 8px;
    border-radius: 4px;
    background-color: #e9ecef;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    transition: width 0.3s ease;
}

.quick-actions {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.action-btn {
    display: block;
    width: 100%;
    padding: 1rem;
    margin-bottom: 1rem;
    border: none;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    text-align: center;
    font-weight: 600;
    transition: transform 0.3s ease;
}

.action-btn:hover {
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.action-btn.success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.action-btn.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.index') }}">Communications</a></li>
                        <li class="breadcrumb-item active">SMS Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">SMS Dashboard</h4>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card success">
                <div class="stats-number">{{ number_format($stats['total_sent']) }}</div>
                <div class="stats-label">Total Sent</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card info">
                <div class="stats-number">{{ number_format($stats['total_delivered']) }}</div>
                <div class="stats-label">Delivered</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card warning">
                <div class="stats-number">{{ number_format($stats['total_scheduled']) }}</div>
                <div class="stats-label">Scheduled</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card danger">
                <div class="stats-number">{{ number_format($stats['total_failed']) }}</div>
                <div class="stats-label">Failed</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number">{{ number_format($stats['today_sent']) }}</div>
                <div class="stats-label">Today Sent</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number">{{ number_format($stats['monthly_sent']) }}</div>
                <div class="stats-label">This Month</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number">₹{{ number_format($stats['total_cost'], 2) }}</div>
                <div class="stats-label">Total Cost</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number">₹{{ number_format($stats['today_cost'], 2) }}</div>
                <div class="stats-label">Today Cost</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Delivery Statistics Chart -->
        <div class="col-xl-8">
            <div class="chart-container">
                <h5 class="mb-3">Delivery Statistics (Last 30 Days)</h5>
                <canvas id="deliveryChart" height="100"></canvas>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-xl-4">
            <div class="quick-actions">
                <h5 class="mb-3">Quick Actions</h5>
                <a href="{{ route('admin.communications.sms.create') }}" class="action-btn">
                    <i class="fas fa-plus mr-2"></i> Send New SMS
                </a>
                <a href="{{ route('admin.communications.sms.index') }}" class="action-btn success">
                    <i class="fas fa-list mr-2"></i> View All Messages
                </a>
                <a href="#" class="action-btn warning" onclick="showBulkSmsModal()">
                    <i class="fas fa-bullhorn mr-2"></i> Bulk SMS
                </a>
                <a href="#" class="action-btn" onclick="showScheduleModal()">
                    <i class="fas fa-clock mr-2"></i> Schedule SMS
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Messages -->
        <div class="col-xl-6">
            <div class="recent-messages">
                <h5 class="mb-3">Recent Messages</h5>
                @forelse($recentMessages as $message)
                <div class="message-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ Str::limit($message->message, 60) }}</h6>
                            <p class="mb-1 text-muted">
                                <small>
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $message->sender->name ?? 'Unknown' }}
                                </small>
                            </p>
                            <div class="d-flex align-items-center">
                                <span class="badge {{ $message->status_badge }} mr-2">{{ ucfirst($message->status) }}</span>
                                <span class="badge {{ $message->priority_badge }} mr-2">{{ ucfirst($message->priority) }}</span>
                                <span class="badge {{ $message->category_badge }}">{{ ucfirst($message->category) }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
                            <br>
                            <small class="text-muted">{{ $message->recipients->count() }} recipients</small>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No messages yet</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Gateway Statistics -->
        <div class="col-xl-6">
            <div class="gateway-stats">
                <h5 class="mb-3">Gateway Performance</h5>
                @forelse($gatewayStats as $gateway)
                <div class="gateway-item">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $gateway->name }}</h6>
                        <p class="mb-1 text-muted">
                            <small>{{ $gateway->provider }} • {{ $gateway->message_count }} messages</small>
                        </p>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $gateway->success_rate }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($gateway->success_rate, 1) }}% success rate</small>
                    </div>
                    <div class="text-right">
                        <span class="badge {{ $gateway->status_badge }}">{{ $gateway->is_active ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-server fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No gateways configured</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Bulk SMS Modal -->
<div class="modal fade" id="bulkSmsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Bulk SMS</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="bulkSmsForm">
                    <div class="form-group">
                        <label>Recipient Type</label>
                        <select class="form-control" name="recipient_type" required>
                            <option value="">Select Type</option>
                            <option value="all">All Users</option>
                            <option value="students">Students Only</option>
                            <option value="parents">Parents Only</option>
                            <option value="staff">Staff Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="message" rows="4" maxlength="160" required placeholder="Enter your message here..."></textarea>
                        <small class="text-muted">Characters: <span id="charCount">0</span>/160</small>
                    </div>
                    <div class="form-group">
                        <label>Priority</label>
                        <select class="form-control" name="priority">
                            <option value="normal">Normal</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" name="category">
                            <option value="notification">Notification</option>
                            <option value="reminder">Reminder</option>
                            <option value="alert">Alert</option>
                            <option value="marketing">Marketing</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="sendBulkSms()">Send Bulk SMS</button>
            </div>
        </div>
    </div>
</div>

<!-- Schedule SMS Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule SMS</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <div class="form-group">
                        <label>Schedule Date & Time</label>
                        <input type="datetime-local" class="form-control" name="scheduled_at" required>
                    </div>
                    <div class="form-group">
                        <label>Recipient Type</label>
                        <select class="form-control" name="recipient_type" required>
                            <option value="">Select Type</option>
                            <option value="all">All Users</option>
                            <option value="students">Students Only</option>
                            <option value="parents">Parents Only</option>
                            <option value="staff">Staff Only</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="message" rows="4" maxlength="160" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="scheduleSms()">Schedule SMS</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize delivery chart
    const ctx = document.getElementById('deliveryChart').getContext('2d');
    const deliveryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($deliveryStats->pluck('date')),
            datasets: [{
                label: 'Total Messages',
                data: @json($deliveryStats->pluck('total')),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            }, {
                label: 'Delivered',
                data: @json($deliveryStats->pluck('delivered')),
                borderColor: '#11998e',
                backgroundColor: 'rgba(17, 153, 142, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Character count for bulk SMS
    $('textarea[name="message"]').on('input', function() {
        $('#charCount').text($(this).val().length);
    });
});

function showBulkSmsModal() {
    $('#bulkSmsModal').modal('show');
}

function showScheduleModal() {
    $('#scheduleModal').modal('show');
}

function sendBulkSms() {
    const form = $('#bulkSmsForm');
    const formData = new FormData(form[0]);
    
    $.ajax({
        url: '{{ route("admin.communications.sms.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Bulk SMS sent successfully');
                $('#bulkSmsModal').modal('hide');
                location.reload();
            } else {
                toastr.error(response.message || 'Failed to send bulk SMS');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(key => {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to send bulk SMS');
            }
        }
    });
}

function scheduleSms() {
    const form = $('#scheduleForm');
    const formData = new FormData(form[0]);
    
    $.ajax({
        url: '{{ route("admin.communications.sms.store") }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                toastr.success('SMS scheduled successfully');
                $('#scheduleModal').modal('hide');
                location.reload();
            } else {
                toastr.error(response.message || 'Failed to schedule SMS');
            }
        },
        error: function(xhr) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                Object.keys(response.errors).forEach(key => {
                    toastr.error(response.errors[key][0]);
                });
            } else {
                toastr.error('Failed to schedule SMS');
            }
        }
    });
}
</script>
@endsection
