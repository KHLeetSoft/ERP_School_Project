@extends('teacher.layout.app')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('page-description', 'Post homework, exam updates, and notices for students')

@section('content')
<div class="card modern-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="fas fa-bullhorn me-2"></i>Announcements</h5>
        <a href="#" class="btn btn-primary" onclick="alert('Coming soon: announcements CRUD and student visibility');">
            <i class="fas fa-plus me-1"></i>New Announcement
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info mb-0">This is a placeholder page. We will add CRUD, scheduling, and class-targeted announcements next.</div>
    </div>
 </div>
@endsection


