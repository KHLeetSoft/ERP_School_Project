@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="d-flex justify-content-between align-items-center mb-2">
		<h5 class="mb-0">AI Papers</h5>
		<a href="{{ route('admin.ai.paper-generator.create') }}" class="btn btn-primary">Generate New</a>
	</div>
	<div class="card shadow-sm border-0">
		<div class="card-body">
			@if(session('success'))
				<div class="alert alert-success">{{ session('success') }}</div>
			@endif
			@if(session('error'))
				<div class="alert alert-danger">{{ session('error') }}</div>
			@endif
			<div class="table-responsive">
				<table id="aiPapersTable" class="table table-striped align-middle w-100">
					<thead class="table-light">
						<tr>
							<th>#</th>
							<th>Subject</th>
							<th>Topic</th>
							<th>Type</th>
							<th>Difficulty</th>
							<th>Questions</th>
							<th>Created</th>
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
<script>
$(function(){
    $('#aiPapersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: { url: "{{ route('admin.ai.paper-generator.datatable') }}" },
        columns: [
            {data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data:'subject', name:'subject'},
            {data:'topic', name:'topic'},
            {data:'type', name:'type'},
            {data:'difficulty', name:'difficulty'},
            {data:'num_questions', name:'num_questions'},
            {data:'created_at', name:'created_at'},
            {data:'actions', name:'actions', orderable:false, searchable:false}
        ]
    });
});
</script>
@endsection
