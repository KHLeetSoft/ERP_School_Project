@extends('admin.layout.app')

@section('title', 'SMS Message Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.communications.sms.index') }}">SMS Messages</a></li>
                        <li class="breadcrumb-item active">Message Details</li>
                    </ol>
                </div>
                <h4 class="page-title">SMS Message Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="header-title">Message Information</h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.communications.sms.edit', $smsMessage->id) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" 
                                onclick="deleteMessage({{ $smsMessage->id }})">
                            <i class="mdi mdi-delete"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Message ID:</strong></td>
                                    <td>#{{ $smsMessage->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>{!! $smsMessage->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <td><strong>Priority:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $smsMessage->priority === 'high' ? 'danger' : ($smsMessage->priority === 'medium' ? 'warning' : 'info') }}">
                                            {{ ucfirst($smsMessage->priority) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($smsMessage->category) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $smsMessage->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Recipients:</strong></td>
                                    <td>{{ $smsMessage->recipients->count() }} {{ Str::plural('recipient', $smsMessage->recipients->count()) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>SMS Count:</strong></td>
                                    <td>{{ $smsMessage->sms_count }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cost:</strong></td>
                                    <td>{{ $smsMessage->cost ? '$' . number_format($smsMessage->cost, 4) : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gateway:</strong></td>
                                    <td>{{ $smsMessage->gateway->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Template:</strong></td>
                                    <td>{{ $smsMessage->template->name ?? 'Custom Message' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($smsMessage->scheduled_at)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="mdi mdi-clock-outline"></i>
                                <strong>Scheduled for:</strong> {{ $smsMessage->scheduled_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($smsMessage->sent_at)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-success">
                                <i class="mdi mdi-check-circle"></i>
                                <strong>Sent at:</strong> {{ $smsMessage->sent_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($smsMessage->failed_at)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="mdi mdi-alert-circle"></i>
                                <strong>Failed at:</strong> {{ $smsMessage->failed_at->format('M d, Y H:i') }}
                                @if($smsMessage->failure_reason)
                                    <br><strong>Reason:</strong> {{ $smsMessage->failure_reason }}
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Message Content:</h5>
                            <div class="border rounded p-3 bg-light">
                                {{ $smsMessage->message }}
                            </div>
                        </div>
                    </div>

                    @if($smsMessage->requires_confirmation)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="mdi mdi-account-check"></i>
                                <strong>Confirmation Required:</strong> 
                                @if($smsMessage->confirmed_at)
                                    Confirmed at {{ $smsMessage->confirmed_at->format('M d, Y H:i') }}
                                @else
                                    Pending confirmation
                                    @if($smsMessage->expires_at)
                                        (Expires: {{ $smsMessage->expires_at->format('M d, Y H:i') }})
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    @if($smsMessage->status === 'draft')
                        <button type="button" class="btn btn-success btn-block mb-2" 
                                onclick="sendNow({{ $smsMessage->id }})">
                            <i class="mdi mdi-send"></i> Send Now
                        </button>
                    @endif

                    @if($smsMessage->status === 'failed')
                        <button type="button" class="btn btn-warning btn-block mb-2" 
                                onclick="retryMessage({{ $smsMessage->id }})">
                            <i class="mdi mdi-refresh"></i> Retry
                        </button>
                    @endif

                    @if($smsMessage->status === 'scheduled')
                        <button type="button" class="btn btn-info btn-block mb-2" 
                                onclick="editSchedule({{ $smsMessage->id }})">
                            <i class="mdi mdi-clock-edit"></i> Edit Schedule
                        </button>
                    @endif

                    <a href="{{ route('admin.communications.sms.create') }}" 
                       class="btn btn-primary btn-block mb-2">
                        <i class="mdi mdi-plus"></i> New Message
                    </a>

                    <a href="{{ route('admin.communications.sms.index') }}" 
                       class="btn btn-secondary btn-block">
                        <i class="mdi mdi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="header-title">Recipient Summary</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $smsMessage->recipients->where('status', 'delivered')->count() }}</h4>
                            <small class="text-muted">Delivered</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $smsMessage->recipients->where('status', 'sent')->count() }}</h4>
                            <small class="text-muted">Sent</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $smsMessage->recipients->where('status', 'failed')->count() }}</h4>
                            <small class="text-muted">Failed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Recipient Details</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Recipient</th>
                                    <th>Phone Number</th>
                                    <th>Status</th>
                                    <th>Sent At</th>
                                    <th>Delivered At</th>
                                    <th>Cost</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($smsMessage->recipients as $recipient)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="mdi mdi-account"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $recipient->recipient_name ?? 'Unknown' }}</h6>
                                                <small class="text-muted">{{ ucfirst($recipient->recipient_type) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $recipient->phone_number }}</td>
                                    <td>{!! $recipient->status_badge !!}</td>
                                    <td>{{ $recipient->sent_at ? $recipient->sent_at->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>{{ $recipient->delivered_at ? $recipient->delivered_at->format('M d, Y H:i') : 'N/A' }}</td>
                                    <td>{{ $recipient->cost ? '$' . number_format($recipient->cost, 4) : 'N/A' }}</td>
                                    <td>
                                        @if($recipient->status === 'failed')
                                            <button type="button" class="btn btn-warning btn-sm" 
                                                    onclick="retryRecipient({{ $recipient->id }})">
                                                <i class="mdi mdi-refresh"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No recipients found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this SMS message? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteMessage(id) {
    if (confirm('Are you sure you want to delete this SMS message?')) {
        document.getElementById('deleteForm').action = `/admin/communications/sms/${id}`;
        document.getElementById('deleteForm').submit();
    }
}

function sendNow(id) {
    if (confirm('Are you sure you want to send this message now?')) {
        fetch(`/admin/communications/sms/${id}/send-now`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while sending the message.');
        });
    }
}

function retryMessage(id) {
    if (confirm('Are you sure you want to retry sending this message?')) {
        fetch(`/admin/communications/sms/${id}/retry`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while retrying the message.');
        });
    }
}

function editSchedule(id) {
    window.location.href = `/admin/communications/sms/${id}/edit`;
}

function retryRecipient(recipientId) {
    if (confirm('Are you sure you want to retry sending to this recipient?')) {
        // Implement recipient retry logic
        alert('Recipient retry functionality will be implemented here.');
    }
}
</script>
@endsection
