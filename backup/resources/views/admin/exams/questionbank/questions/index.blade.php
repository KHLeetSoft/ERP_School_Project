@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm mb-4 border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
			<h4 class="mb-0"><i class="bx bx-list-ul me-2 text-primary"></i> Questions</h4>
			<div class="btn-group" role="group">
				<a href="{{ route('admin.exams.question-bank.questions.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Create</a>
				<a href="{{ route('admin.exams.question-bank.questions.export') }}" class="btn btn-success btn-sm"><i class="fas fa-file-export me-1"></i> Export</a>
				<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#importModal"><i class="fas fa-file-import me-1"></i> Import</button>
			</div>
		</div>
	</div>

	<div class="card shadow-sm border-0">
		<div class="p-3">
			<div class="row g-3 mb-2">
				<div class="col-md-4">
					<label class="form-label">Filter by Category</label>
					<select id="filterCategory" class="form-select">
						<option value="">All</option>
						@foreach($categories as $c)
							<option value="{{ $c->id }}">{{ $c->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="table-responsive">
				<table id="qTable" class="table table-striped align-middle w-100">
					<thead class="table-dark">
						<tr>
							<th>#</th>
							<th>Category</th>
							<th>Type</th>
							<th>Difficulty</th>
							<th>Question</th>
							<th>Marks</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

	<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><h5 class="modal-title">Import Questions</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
				<form action="{{ route('admin.exams.question-bank.questions.import') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Upload File (.xlsx, .csv, .txt)</label>
							<input type="file" class="form-control" name="file" accept=".xlsx,.csv,.txt" required>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Import</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css"/>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
	const table = $('#qTable').DataTable({
		processing: true,
		serverSide: true,
		ajax: {
			url: "{{ route('admin.exams.question-bank.questions.index') }}",
			data: function(d){ d.question_category_id = $('#filterCategory').val(); }
		},
		dom:
            '<"row mb-3 align-items-center"' +
                '<"col-md-6"l>' +
                '<"col-md-6 text-end"f>' +
            '>' +
            '<"row mb-3"<"col-12 text-end"B>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row mt-3"<"col-sm-5"i><"col-sm-7"p>>',
        buttons: [
            { extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
            { extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
            { extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
            { extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' }
        ],
		columns: [
			{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false},
			{data: 'category_name', name: 'category_name'},
			{data: 'type', name: 'type'},
			{data: 'difficulty', name: 'difficulty'},
			{data: 'question_text', name: 'question_text', render:(d)=> d?.slice(0,80) + (d && d.length>80 ? '…' : '')},
			{data: 'marks', name: 'marks'},
			{data: 'status', name: 'status', render:(d)=> d==='active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>'},
			{data: 'actions', name: 'actions', orderable:false, searchable:false}
		]
	});

	$('#filterCategory').on('change', function(){ table.ajax.reload(); });

	$(document).on('click', '.delete-q-btn', function() {
		let action = $(this).data('action');
		if(confirm('Delete this question?')) {
			$.post(action, {_method:'DELETE', _token:'{{ csrf_token() }}'}, function(){
				table.ajax.reload();
			});
		}
	});
});
</script>
@endsection


