@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="card shadow-sm mb-4 border-0">
		<div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
			<h4 class="mb-0"><i class="bx bx-cog me-2 text-primary"></i> Manage Online Exams</h4>
			<div class="btn-group" role="group">
				<a href="{{ route('admin.online-exam.create') }}" class="btn btn-primary btn-sm" title="Create Online Exam" data-bs-toggle="tooltip">
					<i class="fas fa-plus"></i>
				</a>
				<a href="{{ route('admin.online-exam.index') }}" class="btn btn-secondary btn-sm" title="Back to List" data-bs-toggle="tooltip">
					<i class="bx bx-list-ul"></i>
				</a>
			</div>
		</div>
	</div>

	@if(session('success'))
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<i class="fas fa-check-circle me-2"></i> {{ session('success') }}
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	@endif
	@if($errors->any())
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<ul class="mb-0">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
		</div>
	@endif

	<div class="card shadow-sm border-0 mb-3">
		<div class="card-body">
			<form method="GET" action="{{ route('admin.online-exam.manage') }}" class="row g-3 align-items-end">
				<div class="col-md-2">
					<label class="form-label">Class</label>
					<select name="class_id" class="form-select">
						<option value="">All</option>
						@foreach($classes as $class)
							<option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">Section</label>
					<select name="section_id" class="form-select">
						<option value="">All</option>
						@foreach($sections as $section)
							<option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">Subject</label>
					<select name="subject_id" class="form-select">
						<option value="">All</option>
						@foreach($subjects as $subject)
							<option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">Status</label>
					<select name="status" class="form-select">
						<option value="">All</option>
						@foreach(['draft','published','cancelled'] as $st)
							<option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-2">
					<label class="form-label">Start From</label>
					<input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
				</div>
				<div class="col-md-2">
					<label class="form-label">End Till</label>
					<input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
				</div>
				<div class="col-md-12 d-flex gap-2">
					<button class="btn btn-primary btn-sm" type="submit" title="Apply Filters" data-bs-toggle="tooltip"><i class="bx bx-filter"></i></button>
					<a href="{{ route('admin.online-exam.manage') }}" class="btn btn-secondary btn-sm" title="Reset Filters" data-bs-toggle="tooltip"><i class="bx bx-reset"></i></a>
				</div>
			</form>
		</div>
	</div>

	<form method="POST" action="{{ route('admin.online-exam.manage.bulk') }}" id="bulkActionForm">
		@csrf
		<div class="card shadow-sm border-0">
			<div class="card-header bg-white d-flex justify-content-between align-items-center">
				<div class="d-flex gap-2 align-items-center">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" id="selectAll">
						<label class="form-check-label" for="selectAll">Select All</label>
					</div>
				</div>
				<div class="d-flex gap-2">
					<select name="action" class="form-select form-select-sm" required style="max-width: 180px;">
						<option value="" disabled selected>Bulk Action</option>
						<option value="publish">Publish</option>
						<option value="cancel">Cancel</option>
						<option value="delete">Delete</option>
					</select>
					<button type="submit" class="btn btn-outline-primary btn-sm" title="Apply" data-bs-toggle="tooltip"><i class="bx bx-check"></i></button>
				</div>
			</div>
			<div class="table-responsive p-3">
				<table class="table table-striped align-middle w-100">
					<thead class="table-dark">
						<tr>
							<th></th>
							<th>Title</th>
							<th>Class</th>
							<th>Section</th>
							<th>Subject</th>
							<th>Schedule</th>
							<th>Status</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@forelse($onlineExams as $exam)
							<tr>
								<td>
									<input type="checkbox" name="selected_ids[]" value="{{ $exam->id }}" class="row-check">
								</td>
								<td>
									<div class="fw-bold">{{ $exam->title }}</div>
									<small class="text-muted">Total: {{ $exam->total_marks }}, Pass: {{ $exam->passing_marks }}</small>
								</td>
								<td><span class="badge bg-info">{{ $exam->schoolClass->name ?? 'N/A' }}</span></td>
								<td><span class="badge bg-secondary">{{ $exam->section->name ?? 'N/A' }}</span></td>
								<td>{{ $exam->subject->name ?? 'N/A' }}</td>
								<td>
									<div>{{ $exam->start_datetime->format('M d, Y h:i A') }}</div>
									<small class="text-muted">to {{ $exam->end_datetime->format('M d, Y h:i A') }}</small>
								</td>
								<td>
									@switch($exam->status)
										@case('draft')<span class="badge bg-secondary">Draft</span>@break
										@case('published')<span class="badge bg-success">Published</span>@break
										@case('cancelled')<span class="badge bg-danger">Cancelled</span>@break
										@default<span class="badge bg-warning">{{ ucfirst($exam->status) }}</span>
									@endswitch
								</td>
								<td>
									<div class="btn-group" role="group">
										<a href="{{ route('admin.online-exam.show', $exam) }}" class="btn btn-sm " title="View" data-bs-toggle="tooltip"><i class="bx bx-show"></i></a>
										<a href="{{ route('admin.online-exam.edit', $exam) }}" class="btn btn-sm " title="Edit" data-bs-toggle="tooltip"><i class="bx bxs-edit"></i></a>
										<form action="{{ route('admin.online-exam.duplicate', $exam) }}" method="POST" class="d-inline">
											@csrf
											<button class="btn btn-sm" title="Duplicate" data-bs-toggle="tooltip" type="submit"><i class="bx bx-copy"></i></button>
										</form>
										@if($exam->status === 'draft')
											<form action="{{ route('admin.online-exam.publish', $exam) }}" method="POST" class="d-inline">
												@csrf
												@method('PATCH')
												<button class="btn btn-sm " title="Publish" data-bs-toggle="tooltip" type="submit"><i class="bx bx-check"></i></button>
											</form>
										@endif
									</div>
								</td>
							</tr>
						@empty
							<tr>
								<td colspan="8" class="text-center py-4">
									<div class="text-muted">
										<i class="bx bx-cog" style="font-size: 48px;"></i>
										<p class="mt-2">No exams to manage. Try adjusting filters or create one.</p>
										<a href="{{ route('admin.online-exam.create') }}" class="btn btn-primary" title="Create Online Exam" data-bs-toggle="tooltip"><i class="fas fa-plus"></i></a>
									</div>
								</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			@if($onlineExams->hasPages())
				<div class="card-footer">
					{{ $onlineExams->links() }}
				</div>
			@endif
		</div>
	</form>
</div>
@endsection

@section('scripts')
<script>
$(function () {
	// Enable tooltips
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.map(function (tooltipTriggerEl) {return new bootstrap.Tooltip(tooltipTriggerEl)})

	// Select all toggle
	$('#selectAll').on('change', function(){
		$('.row-check').prop('checked', $(this).is(':checked'))
	});
});
</script>
@endsection
