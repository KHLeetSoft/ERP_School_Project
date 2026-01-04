<div class="row g-3">
	<div class="col-md-4">
		<label class="form-label">Membership No</label>
		<input type="text" name="membership_no" value="{{ old('membership_no', $member->membership_no ?? '') }}" class="form-control" placeholder="auto if blank">
		@error('membership_no')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Name</label>
		<input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" class="form-control" required>
		@error('name')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Email</label>
		<input type="email" name="email" value="{{ old('email', $member->email ?? '') }}" class="form-control">
		@error('email')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Phone</label>
		<input type="text" name="phone" value="{{ old('phone', $member->phone ?? '') }}" class="form-control">
		@error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-8">
		<label class="form-label">Address</label>
		<input type="text" name="address" value="{{ old('address', $member->address ?? '') }}" class="form-control">
		@error('address')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Member Type</label>
		<select name="member_type" class="form-select" required>
			@php($type = old('member_type', $member->member_type ?? 'student'))
			<option value="student" {{ $type==='student'?'selected':'' }}>Student</option>
			<option value="teacher" {{ $type==='teacher'?'selected':'' }}>Teacher</option>
			<option value="staff" {{ $type==='staff'?'selected':'' }}>Staff</option>
			<option value="external" {{ $type==='external'?'selected':'' }}>External</option>
		</select>
		@error('member_type')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Joined At</label>
		<input type="datetime-local" name="joined_at" value="{{ old('joined_at', isset($member)? $member->joined_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" class="form-control" required>
		@error('joined_at')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Expiry At</label>
		<input type="datetime-local" name="expiry_at" value="{{ old('expiry_at', isset($member)&&$member->expiry_at? $member->expiry_at->format('Y-m-d\TH:i') : '') }}" class="form-control">
		@error('expiry_at')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-4">
		<label class="form-label">Status</label>
		<select name="status" class="form-select" required>
			@php($st = old('status', $member->status ?? 'active'))
			<option value="active" {{ $st==='active'?'selected':'' }}>Active</option>
			<option value="inactive" {{ $st==='inactive'?'selected':'' }}>Inactive</option>
			<option value="expired" {{ $st==='expired'?'selected':'' }}>Expired</option>
		</select>
		@error('status')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	<div class="col-md-12">
		<label class="form-label">Notes</label>
		<textarea name="notes" rows="3" class="form-control">{{ old('notes', $member->notes ?? '') }}</textarea>
		@error('notes')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
</div>


