@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">Add Subject</h4>
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif
    <form method="POST" action="{{ route('admin.academic.subjects.store') }}">
        @include('admin.academic.subjects._form')
    </form>
</div>
@endsection


