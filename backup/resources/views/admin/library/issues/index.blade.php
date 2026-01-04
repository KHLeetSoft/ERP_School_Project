@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<form id="bulk-delete-form" method="POST" action="{{ route('admin.library.issues.bulk-delete') }}" onsubmit="return confirm('Delete selected issues?')">
			@csrf
			<div class="card-header d-flex justify-content-between align-items-center">
				<h4 class="mb-0"><i class="fas fa-book-reader me-2 text-primary"></i>Book Issues</h4>
				<div class="btn-group" role="group">
					<a href="{{ route('admin.library.issues.create') }}" class="btn btn-primary btn-sm">
						<i class="fas fa-plus me-1"></i> New Issue
					</a>
					<a href="{{ route('admin.library.issues.export') }}" class="btn btn-success btn-sm">
						<i class="fas fa-file-export me-1"></i> Export CSV
					</a>
					<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
						<i class="fas fa-file-import me-1"></i> Import CSV
					</button>
					<button type="button" id="bulk-return-btn" class="btn btn-secondary btn-sm">
						<i class="fas fa-undo me-1"></i> Bulk Return
					</button>
					<button type="submit" form="bulk-form" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete selected issues?')">
						<i class="fas fa-trash-alt me-1"></i> Bulk Delete
					</button>
				</div>
			</div>


			<div class="card-body">
			   
				<br>
				@if(session('success'))
					<div class="alert alert-success">{{ session('success') }}</div>
				@endif

                <div class="d-flex align-items-center gap-2 mb-3">
                    <input type="text" id="filter-student-name" class="form-control form-control-sm" placeholder="Filter by student name" style="max-width:220px;">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="apply-filters">Apply</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filters">Clear</button>
                </div>
                <div class="table-responsive">
					<table id="issues-table" class="table table-striped table-bordered table-hover align-middle w-100">
					<thead class="table-dark">
							<tr>
								<th><input type="checkbox" id="select-all"></th>
								<th>Book</th>
								<th>Student</th>
								<th>Issued</th>
								<th>Due</th>
								<th>Status</th>
								<th>Fine</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</form>
	</div>

	<!-- Import Modal -->
	<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="importModalLabel">Import Issues</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form method="POST" action="{{ route('admin.library.issues.import') }}" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Upload File (.xlsx, .csv)</label>
							<input type="file" name="file" accept=".xlsx,.csv" required class="form-control">
						</div>
						<small class="text-muted">Expected columns: book_id, student_id, issued_at, due_date, returned_at(optional), fine_amount(optional), notes(optional)</small>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Import</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Delete Modal -->
	<div class="modal fade" id="deleteIssueModal" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
		  <div class="modal-body">Are you sure you want to delete this issue?</div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
			<form id="issueDeleteForm" method="POST" action="">
			  @csrf @method('DELETE')
			  <button type="submit" class="btn btn-danger">Delete</button>
			</form>
		  </div>
		</div>
	  </div>
	</div>
</div>
@endsection
@section('scripts')
<script>
let table;
function loadTable() {
	if (table) table.destroy();
	table = $('#issues-table').DataTable({
		processing: true,
		serverSide: true,
		order: [[3, 'desc']],
        ajax: { 
            url: "{{ route('admin.library.issues.index') }}", 
            type: 'GET',
            data: function (d) {
                d.student_name = document.getElementById('filter-student-name').value;
            }
        },
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
			{ extend: 'csv', className: 'btn btn-success btn-sm rounded-pill', text: '<i class="fas fa-file-csv me-1"></i> CSV' },
			{ extend: 'pdf', className: 'btn btn-danger btn-sm rounded-pill', text: '<i class="fas fa-file-pdf me-1"></i> PDF' },
			{ extend: 'print', className: 'btn btn-warning btn-sm rounded-pill', text: '<i class="fas fa-print me-1"></i> Print' },
			{ extend: 'copy', className: 'btn btn-info btn-sm rounded-pill', text: '<i class="fas fa-copy me-1"></i> Copy' }
		],
		columns: [
			{ data: 'select', name: 'select', orderable: false, searchable: false },
			{ data: 'book_title', name: 'book_title' },
			{ data: 'student_name', name: 'student_name' },
			{ data: 'issued_at', name: 'issued_at' },
			{ data: 'due_date', name: 'due_date' },
			{ data: 'status', name: 'status' },
			{ data: 'fine_amount', name: 'fine_amount' },
			{ data: 'action', name: 'action', orderable: false, searchable: false }
		]
	});
}

$(document).ready(function(){
	loadTable();
    $('#apply-filters').on('click', function(){ loadTable(); });
    $('#clear-filters').on('click', function(){ $('#filter-student-name').val(''); loadTable(); });
	$(document).on('click', '.delete-issue-btn', function() {
		const id = $(this).data('id');
		const actionUrl = `{{ url('admin/library/issues') }}/${id}`;
		$('#issueDeleteForm').attr('action', actionUrl);
		const modal = new bootstrap.Modal(document.getElementById('deleteIssueModal'));
		modal.show();
	});
	$('#select-all').on('change', function(e){
		$('#issues-table tbody input.row-select').prop('checked', this.checked);
	});
	$('#bulk-delete-form').on('submit', function(){
		this.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
		$('#issues-table tbody input.row-select:checked').each((_, el) => {
			const hidden = document.createElement('input');
			hidden.type = 'hidden'; hidden.name = 'ids[]'; hidden.value = el.value;
			this.appendChild(hidden);
		});
	});
	$('#bulk-return-btn').on('click', function(){
		const ids = $('#issues-table tbody input.row-select:checked').map((_, el) => el.value).get();
		if (ids.length === 0) { alert('Select at least one row'); return; }
		const form = document.createElement('form');
		form.method = 'POST'; form.action = `{{ route('admin.library.issues.bulk-return') }}`;
		form.innerHTML = `@csrf`;
		ids.forEach(id => {
			const input = document.createElement('input'); input.type = 'hidden'; input.name = 'ids[]'; input.value = id; form.appendChild(input);
		});
		document.body.appendChild(form); form.submit();
	});
});
</script>
@endsection


