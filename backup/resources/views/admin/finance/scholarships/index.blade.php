@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm border-0">
		<div class="card-header d-flex justify-content-between align-items-center">
			<h6 class="mb-0">Scholarships</h6>
			<div class="d-flex gap-2">
				<a href="{{ route('admin.finance.scholarships.dashboard') }}" class="btn btn-sm btn-info"><i class="bx bx-bar-chart"></i> Dashboard</a>
				<a href="{{ route('admin.finance.scholarships.create') }}" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i> New</a>
				<a href="{{ route('admin.finance.scholarships.export') }}" class="btn btn-sm btn-success"><i class="bx bx-download"></i> Export</a>
				<button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bx bx-upload"></i> Import</button>
			</div>
		</div>
		<div class="card-body">
			<table class="table table-striped align-middle" id="scholarshipsTable">
				<thead class="table-dark">
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Code</th>
						<th>Amount</th>
						<th>Status</th>
						<th>Awarded</th>
						<th class="text-end">Actions</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>

	<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><h6 class="modal-title">Import Scholarships</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
				<form method="POST" action="{{ route('admin.finance.scholarships.import') }}" enctype="multipart/form-data">
					@csrf
					<div class="modal-body">
						<input type="file" name="file" class="form-control" accept=".xlsx,.csv" required>
						<div class="form-text">Columns: name, code, amount, status, awarded_date, notes</div>
					</div>
					<div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal">Cancel</button><button class="btn btn-dark" type="submit"><i class="bx bx-upload"></i> Import</button></div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
(function(){
	$(function(){
		$('#scholarshipsTable').DataTable({
			processing:true, serverSide:true,
			ajax:'{{ route('admin.finance.scholarships.index') }}',
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
			columns:[
				{ data: 'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
				{ data: 'name', name:'name' },
				{ data: 'code', name:'code' },
				{ data: 'amount', name:'amount' },
				{ data: 'status', name:'status' },
				{ data: 'awarded_date', name:'awarded_date' },
				{ data: 'actions', name:'actions', orderable:false, searchable:false, className:'text-end' },
			],
			order:[[1,'asc']], pageLength:25, responsive:true
		});
	});
})();
</script>
@endsection


