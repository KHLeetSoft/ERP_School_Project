<div class="btn-group">
	<a href="{{ route('admin.finance.scholarships.show', $r) }}" class="btn btn-sm btn-light"><i class="bx bx-show"></i></a>
	<a href="{{ route('admin.finance.scholarships.edit', $r) }}" class="btn btn-sm btn-primary"><i class="bx bx-edit"></i></a>
	<form method="POST" action="{{ route('admin.finance.scholarships.destroy', $r) }}" onsubmit="return confirm('Delete scholarship?')">
		@csrf
		@method('DELETE')
		<button type="submit" class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></button>
	</form>
</div>


