@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Create Notification</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.result-announcement.notification.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message *</label>
                            <textarea name="message" rows="4" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link to Announcement (optional)</label>
                            <select name="result_announcement_id" class="form-select">
                                <option value="">Select...</option>
                                @foreach($announcements as $a)
                                    <option value="{{ $a->id }}">{{ $a->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Target Audience</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="target_audience[]" value="students" id="aud_students" checked>
                                <label class="form-check-label" for="aud_students">Students</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="target_audience[]" value="parents" id="aud_parents" checked>
                                <label class="form-check-label" for="aud_parents">Parents</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Channels</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="channels[]" value="database" id="ch_db" checked>
                                <label class="form-check-label" for="ch_db">In-App (Database)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="channels[]" value="email" id="ch_email" disabled>
                                <label class="form-check-label" for="ch_email">Email (coming soon)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="channels[]" value="sms" id="ch_sms" disabled>
                                <label class="form-check-label" for="ch_sms">SMS (coming soon)</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Schedule (optional)</label>
                            <input type="datetime-local" name="scheduled_at" class="form-control">
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-save me-1"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Notifications</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle" id="notificationsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Announcement</th>
                                <th>Status</th>
                                <th>Scheduled</th>
                                <th>Sent</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function(){
    $('#notificationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.result-announcement.notification.index") }}',
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Left side: Show
                '<"col-md-6 text-end"f>' +    // Right side: Search
            '>' +
            '<"row mb-3"' +
                '<"col-12 text-end"B>' +      // Next line: Buttons full width right aligned
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
              buttons: [
              {
                extend: 'csv',
                className: 'btn btn-success btn-sm rounded-pill',
                text: '<i class="fas fa-file-csv me-1"></i> CSV'
              },
              {
                extend: 'pdf',
                className: 'btn btn-danger btn-sm rounded-pill',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF'
              },
              {
                extend: 'print',
                className: 'btn btn-warning btn-sm rounded-pill',
                text: '<i class="fas fa-print me-1"></i> Print'
                
              },
              {
                extend: 'copy',
                className: 'btn btn-info btn-sm rounded-pill',
                text: '<i class="fas fa-copy me-1"></i> Copy'
              }
            ],
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'announcement', name: 'announcement' },
            { data: 'status', name: 'status' },
            { data: 'scheduled_at', name: 'scheduled_at' },
            { data: 'sent_at', name: 'sent_at' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(id, type, row){
                    return `<form method="POST" action="${'{{ url('admin/result-announcement/notification') }}'}/${id}/send" onsubmit="return confirm('Send now?')">
                                {{ csrf_field() }}
                                <button class="btn btn-sm btn-primary">Send</button>
                            </form>`;
                }
            }
        ]
    });
});
</script>
@endsection
