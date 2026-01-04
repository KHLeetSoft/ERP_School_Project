@csrf
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $subject->name ?? '') }}" required />
    </div>
    <div class="col-md-6">
        <label class="form-label">Code</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $subject->code ?? '') }}" required />
    </div>
    <div class="col-md-4">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            @php($val = old('type', $subject->type ?? 'theory'))
            <option value="theory" @selected($val==='theory')>Theory</option>
            <option value="practical" @selected($val==='practical')>Practical</option>
            <option value="lab" @selected($val==='lab')>Lab</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Credit Hours</label>
        <input type="number" name="credit_hours" class="form-control" min="0" max="10" value="{{ old('credit_hours', $subject->credit_hours ?? '') }}" />
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @php($s = old('status', ($subject->status ?? true) ? 1 : 0))
            <option value="1" @selected($s==1)>Active</option>
            <option value="0" @selected($s==0)>Inactive</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="4" class="form-control">{{ old('description', $subject->description ?? '') }}</textarea>
    </div>
    <div class="col-12 text-end">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('admin.academic.subjects.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>


