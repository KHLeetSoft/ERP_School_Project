@extends('admin.layout.app')

@section('title','Edit Visitor Purpose')

@section('content')
<div class="card">
  <div class="card-header"><h4>Edit Visitor Purpose</h4></div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.office.visitorspurpose.update', $purpose->id) }}">
      @csrf @method('PUT')
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Name</label>
          <input name="name" class="form-control" value="{{ old('name', $purpose->name) }}" required>
          @error('name')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="col-md-6 mb-3">
          <label class="form-label">Status</label>
          <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" {{ $purpose->status ? 'checked' : '' }}>
            <label class="form-check-label" for="statusSwitch">Active</label>
          </div>
          @error('status')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="col-md-12 mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $purpose->description) }}</textarea>
          @error('description')
            <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Update</button>
          <a href="{{ route('admin.office.visitorspurpose.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection