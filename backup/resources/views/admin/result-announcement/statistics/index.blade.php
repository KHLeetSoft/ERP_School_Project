@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Generate Statistics</h6></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.result-announcement.statistics.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Announcement (optional)</label>
                            <select name="result_announcement_id" class="form-select">
                                <option value="">Select...</option>
                                @foreach($announcements as $a)
                                    <option value="{{ $a->id }}">{{ $a->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Filters (JSON, optional)</label>
                            <textarea name="filters" rows="3" class="form-control" placeholder='{"class_id":1,"section_id":2}'></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary"><i class="bx bx-cog me-1"></i> Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Generated Statistics</h6>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.result-announcement.statistics.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bx bx-bar-chart"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.result-announcement.statistics.create') }}" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus"></i> New
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle" id="statisticsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Announcement</th>
                                <th>Generated</th>
                                <th class="text-end">Actions</th>
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
    $('#statisticsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("admin.result-announcement.statistics.index") }}',
        dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +             // Left side: Show
                '<"col-md-6 text-end"f>' +    // Right side: Search
            '>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'announcement', name: 'announcement' },
            { data: 'generated_at', name: 'generated_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-end' },
        ],
        order: [[3, 'desc']],
        pageLength: 25,
        responsive: true,
    });

    $(document).on('click', '.js-delete-stat', function(){
        const url = $(this).data('url');
        if(!confirm('Delete this statistic?')) return;
        $.ajax({
            url: url,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(){
                $('#statisticsTable').DataTable().ajax.reload(null, false);
            }
        });
    });
});
</script>
@endsection


