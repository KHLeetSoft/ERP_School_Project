@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card">
		<form id="bulk-delete-form" method="POST" action="{{ route('admin.library.members.bulk-delete') }}" onsubmit="return confirm('Delete selected members?')">
			@csrf
			<div class="card-header d-flex justify-content-between align-items-center">
				<h4 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Library Members</h4>
				<div class="btn-group" role="group">
					<a href="{{ route('admin.library.members.create') }}" class="btn btn-primary btn-sm">
						<i class="fas fa-plus me-1"></i> New Member
					</a>
					<a href="{{ route('admin.library.members.export') }}" class="btn btn-success btn-sm">
						<i class="fas fa-file-export me-1"></i> Export CSV
					</a>
					<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
						<i class="fas fa-file-import me-1"></i> Import CSV
					</button>
					<button type="submit" form="bulk-form" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete selected members?')">
						<i class="fas fa-trash-alt me-1"></i> Bulk Delete
					</button>
				</div>
			</div>


			<div class="card-body">
				@if(session('success'))
					<div class="alert alert-success">{{ session('success') }}</div>
				@endif

				<div class="row g-2 mb-3">
					<div class="col-md-3">
						<input type="text" id="filter-name" class="form-control form-control-sm" placeholder="Name">
					</div>
					<div class="col-md-3">
						<select id="filter-type" class="form-select form-select-sm">
							<option value="">All Types</option>
							<option value="student">Student</option>
							<option value="teacher">Teacher</option>
							<option value="staff">Staff</option>
							<option value="external">External</option>
						</select>
					</div>
					<div class="col-md-3">
						<select id="filter-status" class="form-select form-select-sm">
							<option value="">All Status</option>
							<option value="active">Active</option>
							<option value="inactive">Inactive</option>
							<option value="expired">Expired</option>
						</select>
					</div>
					<div class="col-md-3 d-flex gap-2">
						<button type="button" class="btn btn-outline-primary btn-sm" id="apply-filters">Apply</button>
						<button type="button" class="btn btn-outline-secondary btn-sm" id="clear-filters">Clear</button>
					</div>
				</div>

				<div class="table-responsive">
					<table id="members-table" class="table table-striped table-bordered table-hover align-middle w-100">
					<thead class="table-dark">
							<tr>
								<th><input type="checkbox" id="select-all"></th>
								<th>Membership No</th>
								<th>Name</th>
								<th>Type</th>
								<th>Status</th>
								<th>Expiry</th>
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
					<h5 class="modal-title" id="importModalLabel">Import Members</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form method="POST" action="{{ route('admin.library.members.import') }}" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label class="form-label">Upload File (.xlsx, .csv)</label>
							<input type="file" name="file" accept=".xlsx,.csv" required class="form-control">
						</div>
						<small class="text-muted">Expected columns: membership_no(optional), name, email(optional), phone(optional), address(optional), member_type(optional), joined_at(optional), expiry_at(optional), status(optional), notes(optional)</small>
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
<script>
let table;
function loadTable() {
	if (table) table.destroy();
	table = $('#members-table').DataTable({
		processing: true,
		serverSide: true,
		order: [[5, 'asc']],
		ajax: { 
			url: "{{ route('admin.library.members.index') }}", 
			type: 'GET',
			data: function (d) {
				d.name = document.getElementById('filter-name').value;
				d.member_type = document.getElementById('filter-type').value;
				d.status = document.getElementById('filter-status').value;
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
			{ data: 'membership_no', name: 'membership_no' },
			{ data: 'name', name: 'name' },
			{ data: 'member_type', name: 'member_type' },
			{ data: 'status', name: 'status' },
			{ data: 'expiry_at', name: 'expiry_at' },
			{ data: 'action', name: 'action', orderable: false, searchable: false }
		]
	});
}

$(document).ready(function(){
	loadTable();
	$('#apply-filters').on('click', function(){ loadTable(); });
	$('#clear-filters').on('click', function(){ $('#filter-name').val(''); $('#filter-type').val(''); $('#filter-status').val(''); loadTable(); });
	$('#select-all').on('change', function(e){ $('#members-table tbody input.row-select').prop('checked', this.checked); });
	$('#bulk-delete-form').on('submit', function(){
		this.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
		$('#members-table tbody input.row-select:checked').each((_, el) => {
			const hidden = document.createElement('input');
			hidden.type = 'hidden'; hidden.name = 'ids[]'; hidden.value = el.value;
			this.appendChild(hidden);
		});
	});
});
</script>
@endsection


