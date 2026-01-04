@extends('teacher.layout.app')

@section('title', 'Digital Whiteboard')
@section('page-title', 'Digital Whiteboard')
@section('page-description', 'Share live lecture notes or doodles with students (Coming Soon)')

@section('content')
<div class="card modern-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="fas fa-chalkboard me-2"></i>Digital Whiteboard</h5>
        <a href="#" class="btn btn-outline-primary" onclick="alert('SaaS whiteboard module coming soon!')">
            <i class="fas fa-rocket me-1"></i>Preview
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info">Placeholder: We will integrate a canvas-based whiteboard with save/share options.</div>
        <div class="p-5 text-center text-muted border rounded">
            <i class="fas fa-chalkboard fa-3x mb-3"></i>
            <div>Sketch area will appear here in the next iteration.</div>
        </div>
    </div>
 </div>
@endsection


