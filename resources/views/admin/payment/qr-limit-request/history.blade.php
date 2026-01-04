@extends('admin.layout.app')

@section('title', 'QR Limit Request History')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payment.qr-limit-requests.index') }}">QR Limit Request</a></li>
                        <li class="breadcrumb-item active">History</li>
                    </ol>
                </div>
                <h4 class="page-title">QR Limit Request History</h4>
            </div>
        </div>
    </div>

    <!-- Current Status Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Current QR Code Status</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary">{{ $school->qr_code_limit }}</h3>
                                <p class="text-muted mb-0">Current Limit</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success">{{ $school->qr_codes_generated }}</h3>
                                <p class="text-muted mb-0">Generated</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info">{{ $school->getRemainingQrCodes() }}</h3>
                                <p class="text-muted mb-0">Remaining</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                @if($school->needsPaymentForQrCodes())
                                    <span class="badge bg-warning fs-6">Payment Required</span>
                                @elseif($school->qr_limit_paid)
                                    <span class="badge bg-success fs-6">Paid</span>
                                @else
                                    <span class="badge bg-info fs-6">Free</span>
                                @endif
                                <p class="text-muted mb-0 mt-1">Status</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Request History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="header-title">Request History</h4>
                            <p class="text-muted mb-0">All your QR code limit requests</p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.payment.qr-limit-requests.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i> New Request
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Request Date</th>
                                        <th>Current Limit</th>
                                        <th>Requested Limit</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Processed By</th>
                                        <th>Processed Date</th>
                                        <th>Admin Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $index => $request)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $request->created_at->format('d M Y H:i A') }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $request->current_limit }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $request->requested_limit }}</span>
                                            </td>
                                            <td>{{ Str::limit($request->reason, 50) }}</td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($request->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @else
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->processedBy)
                                                    {{ $request->processedBy->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->processed_at)
                                                    {{ $request->processed_at->format('d M Y H:i A') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($request->admin_notes)
                                                    <span class="text-muted" title="{{ $request->admin_notes }}">
                                                        {{ Str::limit($request->admin_notes, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title rounded-circle bg-soft-primary text-primary font-size-24">
                                    <i class="mdi mdi-history"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">No Requests Found</h5>
                            <p class="text-muted">You haven't made any QR code limit requests yet.</p>
                            <a href="{{ route('admin.payment.qr-limit-requests.index') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus me-1"></i> Make Your First Request
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[title]').tooltip();
});
</script>
@endsection
