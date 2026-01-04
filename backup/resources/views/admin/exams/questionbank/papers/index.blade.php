@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm mb-4 border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
			<h4 class="mb-0"><i class="bx bx-file me-2 text-primary"></i> Question Papers</h4>
			<a href="{{ route('admin.exams.question-bank.papers.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Generate</a>
		</div>
	</div>

	<div class="card shadow-sm border-0">
		<div class="p-3">
			<div class="table-responsive">
				<table id="paperTable" class="table table-striped align-middle w-100">
					<thead class="table-dark">
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Subject</th>
							<th>Total Marks</th>
							<th>Duration</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
				</table>
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
$(function(){
	$('#paperTable').DataTable({
		processing: true,
		serverSide: true,
		ajax: { url: "{{ route('admin.exams.question-bank.papers.index') }}" },
		columns: [
			{data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
			{data:'title', name:'title'},
			{data:'subject_name', name:'subject_name'},
			{data:'total_marks', name:'total_marks'},
			{data:'duration_mins', name:'duration_mins'},
			{data:'status', name:'status'},
			{data:'actions', name:'actions', orderable:false, searchable:false}
		]
	});
});
</script>
@endsection


