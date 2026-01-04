<div class="row g-3">
	<div class="col-md-6">
		<label class="form-label">Book</label>
		<select name="book_id" class="form-select" required>
			<option value="">Select Book</option>
			@foreach($books as $book)
				<option value="{{ is_object($book) ? $book->id : $book }}" {{ (old('book_id', $issue->book_id ?? '') == (is_object($book) ? $book->id : $book)) ? 'selected' : '' }}>
					{{ is_object($book) ? $book->title : $book }}
				</option>
			@endforeach
		</select>
		@error('book_id')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-6">
		<label class="form-label">Student</label>
		<select name="student_id" class="form-select" required>
			<option value="">Select Student</option>
			@foreach($students as $student)
				<option value="{{ $student->id }}" {{ (old('student_id', $issue->student_id ?? '') == $student->id) ? 'selected' : '' }}>
					{{ $student->full_name }}
				</option>
			@endforeach
		</select>
		@error('student_id')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Issued At</label>
		<input type="datetime-local" name="issued_at" class="form-control" value="{{ old('issued_at', isset($issue) ? $issue->issued_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
		@error('issued_at')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Due Date</label>
		<input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date', isset($issue) ? $issue->due_date->format('Y-m-d\TH:i') : now()->addDays(7)->format('Y-m-d\TH:i')) }}" required>
		@error('due_date')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Fine Amount</label>
		<input type="number" step="0.01" name="fine_amount" class="form-control" value="{{ old('fine_amount', $issue->fine_amount ?? 0) }}">
		@error('fine_amount')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-12">
		<label class="form-label">Notes</label>
		<textarea name="notes" rows="3" class="form-control">{{ old('notes', $issue->notes ?? '') }}</textarea>
		@error('notes')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
</div>


