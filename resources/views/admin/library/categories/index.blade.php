@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<form id="bulk-delete-form" method="POST" action="{{ route('admin.library.categories.bulk-delete') }}" onsubmit="return confirm('Delete selected categories?')">
			@csrf
			<div class="card-header d-flex justify-content-between align-items-center">
				<h4 class="mb-0"><i class="fas fa-tags me-2 text-primary"></i>Categories</h4>
				<div class="btn-group" role="group">
					<a href="{{ route('admin.library.categories.create') }}" class="btn btn-primary btn-sm">
						<i class="fas fa-plus me-1"></i> Add Category
					</a>
					<a href="{{ route('admin.library.categories.export') }}" class="btn btn-success btn-sm">
						<i class="fas fa-file-export me-1"></i> Export CSV
					</a>
					<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
						<i class="fas fa-file-import me-1"></i> Import CSV
					</button>
					<button type="submit" form="bulk-form" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete selected categories?')">
						<i class="fas fa-trash-alt me-1"></i> Bulk Delete
					</button>
				</div>
			</div>


			<div class="card-body">
				@if(session('success'))
					<div class="alert alert-success">{{ session('success') }}</div>
				@endif

				<div class="table-responsive">
					<table id="categories-table" class="table table-striped table-bordered table-hover align-middle w-100">
					<thead class="table-dark">
							<tr>
								<th><input type="checkbox" id="select-all"></th>
								<th>Name</th>
								<th>Slug</th>
								<th>Status</th>
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
					<h5 class="modal-title" id="importModalLabel">Import Categories</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form method="POST" action="{{ route('admin.library.categories.import') }}" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Upload File (.xlsx, .csv)</label>
							<input type="file" name="file" accept=".xlsx,.csv" required class="form-control">
						</div>
						<small class="text-muted">Expected columns: name, slug(optional), description(optional), status(optional)</small>
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
	<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header"><h5 class="modal-title">Confirm Delete</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
		  <div class="modal-body">Are you sure you want to delete this category?</div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
			<form id="categoryDeleteForm" method="POST" action="">
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
		table = $('#categories-table').DataTable({
			processing: true,
			serverSide: true,
			order: [[1, 'asc']],
			ajax: {
				url: "{{ route('admin.library.categories.index') }}",
				type: 'GET',
				data: function (d) {
					d._token = "{{ csrf_token() }}";
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
				{ data: 'name', name: 'name' },
				{ data: 'slug', name: 'slug' },
				{ data: 'status', name: 'status' },
				{ data: 'action', name: 'action', orderable: false, searchable: false }
			]
		});
	}

	$(document).ready(function(){
		loadTable();
		$(document).on('click', '.delete-category-btn', function() {
			const id = $(this).data('id');
			const actionUrl = `{{ url('admin/library/categories') }}/${id}`;
			$('#categoryDeleteForm').attr('action', actionUrl);
			const modal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
			modal.show();
		});
		document.getElementById('select-all').addEventListener('change', function(e) {
			const checked = e.target.checked;
			$('#categories-table tbody input.row-select').prop('checked', checked);
		});
		document.getElementById('bulk-delete-form').addEventListener('submit', function(e) {
			this.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
			$('#categories-table tbody input.row-select:checked').each((_, el) => {
				const hidden = document.createElement('input');
				hidden.type = 'hidden';
				hidden.name = 'ids[]';
				hidden.value = el.value;
				document.getElementById('bulk-delete-form').appendChild(hidden);
			});
		});
	});
</script>
@endsection


