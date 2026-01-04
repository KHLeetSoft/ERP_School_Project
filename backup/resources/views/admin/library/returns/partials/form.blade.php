<div class="row g-3">
	<div class="col-md-6">
		<label class="form-label">Issue</label>
		<select name="book_issue_id" class="form-select" required>
			<option value="">Select Issue</option>
			@foreach($issues as $issue)
				<option value="{{ $issue->id }}" {{ (old('book_issue_id', $return->book_issue_id ?? '') == $issue->id) ? 'selected' : '' }}>
					#{{ $issue->id }} - {{ $issue->book->title }} ({{ $issue->student->full_name }})
				</option>
			@endforeach
		</select>
		@error('book_issue_id')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-3">
		<label class="form-label">Returned At</label>
		<input type="datetime-local" name="returned_at" class="form-control" value="{{ old('returned_at', isset($return) ? $return->returned_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
		@error('returned_at')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-3">
		<label class="form-label">Fine Paid</label>
		<input type="number" step="0.01" name="fine_paid" class="form-control" value="{{ old('fine_paid', $return->fine_paid ?? 0) }}">
		@error('fine_paid')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-6">
		<label class="form-label">Condition</label>
		<input type="text" name="condition" class="form-control" value="{{ old('condition', $return->condition ?? '') }}" placeholder="e.g., Good, Damaged">
		@error('condition')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-12">
		<label class="form-label">Remarks</label>
		<textarea name="remarks" rows="3" class="form-control">{{ old('remarks', $return->remarks ?? '') }}</textarea>
		@error('remarks')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
</div>


