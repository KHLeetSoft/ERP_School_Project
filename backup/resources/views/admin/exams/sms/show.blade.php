@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="bi bi-chat-text me-2 text-primary"></i> SMS Campaign</h4>
        <div class="d-flex gap-2">
            <form method="POST" action="{{ route('admin.exams.sms.send', $sms->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send"></i> Send Now</button>
            </form>
            <a href="{{ route('admin.exams.sms.recipients.index', $sms->id) }}" class="btn btn-secondary btn-sm"><i class="bi bi-people"></i> View Recipients</a>
            <a href="{{ route('admin.exams.sms.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Back</a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Title</div><div class="fw-semibold">{{ $sms->title }}</div></div>
                <div class="col-md-4"><div class="text-muted">Exam</div><div class="fw-semibold">{{ optional($sms->exam)->title ?? '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">Audience</div><div class="fw-semibold text-capitalize">{{ $sms->audience_type }}</div></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><div class="text-muted">Class / Section</div><div class="fw-semibold">{{ $sms->class_name }} {{ $sms->section_name }}</div></div>
                <div class="col-md-4"><div class="text-muted">Schedule</div><div class="fw-semibold">{{ optional($sms->schedule_at)?->format('Y-m-d H:i') ?? '-' }}</div></div>
                <div class="col-md-4"><div class="text-muted">Status</div><div class="fw-semibold text-capitalize">{{ $sms->status }}</div></div>
            </div>
            <div class="row">
                <div class="col-12"><div class="text-muted">Message Template</div><pre class="mb-0">{{ $sms->message_template }}</pre></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <strong>Recipients</strong>
            <div>
                <span class="badge bg-success">Sent: {{ $sms->sent_count }}</span>
                <span class="badge bg-danger">Failed: {{ $sms->failed_count }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Recipient ID</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Sent At</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sms->recipients()->latest()->limit(200)->get() as $i => $r)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td class="text-capitalize">{{ $r->recipient_type }}</td>
                                <td>{{ $r->recipient_id }}</td>
                                <td>{{ $r->phone }}</td>
                                <td>
                                    @php $map=['pending'=>'secondary','sent'=>'success','failed'=>'danger']; @endphp
                                    <span class="badge bg-{{ $map[$r->status] ?? 'secondary' }} text-uppercase">{{ $r->status }}</span>
                                </td>
                                <td>{{ optional($r->sent_at)?->format('Y-m-d H:i') }}</td>
                                <td class="text-muted">{{ \Illuminate\Support\Str::limit($r->error, 60) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">No recipients logged yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="small text-muted mt-2">Showing latest 200 recipients.</div>
            </div>
        </div>
    </div>
</div>
@endsection


