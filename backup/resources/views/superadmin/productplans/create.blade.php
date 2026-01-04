@extends('superadmin.app')

@section('content')
<div class="card">
  <div class="card-header"><h4>Create Product Plan</h4></div>
  <div class="card-body">
    <form method="POST" action="{{ route('superadmin.productplans.store') }}">
      @csrf
      <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Price</label>
        <input type="number" name="price" class="form-control" step="0.01" required>
      </div>
      <div class="mb-3">
        <label>Features (comma separated)</label>
        <textarea name="features" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label>Max Users</label>
        <input type="number" name="max_users" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Create Plan</button>
    </form>
  </div>
</div>
@endsection
