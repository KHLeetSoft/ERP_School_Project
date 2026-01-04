@extends('teacher.layout.app')

@section('title', 'My Resources')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-book"></i> My Resources
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher.resources.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Resource
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Resource Statistics -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $resourceStats['total_resources'] }}</h3>
                                    <p>Total Resources</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ $resourceStats['published_resources'] }}</h3>
                                    <p>Published</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $resourceStats['total_downloads'] }}</h3>
                                    <p>Total Downloads</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>{{ $resourceStats['total_views'] }}</h3>
                                    <p>Total Views</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('teacher.resources.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="file" {{ request('type') == 'file' ? 'selected' : '' }}>File</option>
                                        <option value="link" {{ request('type') == 'link' ? 'selected' : '' }}>Link</option>
                                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                                        <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                                        <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document</option>
                                        <option value="presentation" {{ request('type') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                                        <option value="worksheet" {{ request('type') == 'worksheet' ? 'selected' : '' }}>Worksheet</option>
                                        <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="visibility" class="form-select">
                                        <option value="">All Visibility</option>
                                        <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                                        <option value="shared" {{ request('visibility') == 'shared' ? 'selected' : '' }}>Shared</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Resources Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Visibility</th>
                                    <th>Views</th>
                                    <th>Downloads</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $resource)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($resource->is_pinned)
                                                <i class="fas fa-thumbtack text-warning me-2" title="Pinned"></i>
                                            @endif
                                            @if($resource->is_featured)
                                                <i class="fas fa-star text-warning me-2" title="Featured"></i>
                                            @endif
                                            <div>
                                                <strong>{{ $resource->title }}</strong>
                                                @if($resource->description)
                                                <br><small class="text-muted">{{ Str::limit($resource->description, 50) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge" style="background-color: {{ $resource->category->color }}; color: white;">
                                            {{ $resource->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $resource->type_color }}">
                                            <i class="bx {{ $resource->type_icon }}"></i> {{ ucfirst($resource->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @switch($resource->status)
                                            @case('published')
                                                <span class="badge badge-success">Published</span>
                                                @break
                                            @case('draft')
                                                <span class="badge badge-warning">Draft</span>
                                                @break
                                            @case('archived')
                                                <span class="badge badge-secondary">Archived</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($resource->visibility)
                                            @case('public')
                                                <span class="badge badge-info">Public</span>
                                                @break
                                            @case('private')
                                                <span class="badge badge-secondary">Private</span>
                                                @break
                                            @case('shared')
                                                <span class="badge badge-primary">Shared</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>{{ $resource->view_count }}</td>
                                    <td>{{ $resource->download_count }}</td>
                                    <td>{{ $resource->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('teacher.resources.show', $resource) }}" class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.resources.edit', $resource) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($resource->file_path)
                                            <a href="{{ route('teacher.resources.download', $resource) }}" class="btn btn-success btn-sm" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($resource->status === 'published')
                                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('unpublish-{{ $resource->id }}').submit();">
                                                            <i class="fas fa-eye-slash"></i> Unpublish
                                                        </a></li>
                                                    @else
                                                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('publish-{{ $resource->id }}').submit();">
                                                            <i class="fas fa-eye"></i> Publish
                                                        </a></li>
                                                    @endif
                                                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('featured-{{ $resource->id }}').submit();">
                                                        <i class="fas fa-star"></i> {{ $resource->is_featured ? 'Remove from Featured' : 'Add to Featured' }}
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('pinned-{{ $resource->id }}').submit();">
                                                        <i class="fas fa-thumbtack"></i> {{ $resource->is_pinned ? 'Unpin' : 'Pin' }}
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('archive-{{ $resource->id }}').submit();">
                                                        <i class="fas fa-archive"></i> Archive
                                                    </a></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-{{ $resource->id }}').submit();">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Hidden Forms -->
                                        <form id="publish-{{ $resource->id }}" action="{{ route('teacher.resources.publish', $resource) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="unpublish-{{ $resource->id }}" action="{{ route('teacher.resources.unpublish', $resource) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="featured-{{ $resource->id }}" action="{{ route('teacher.resources.toggle-featured', $resource) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="pinned-{{ $resource->id }}" action="{{ route('teacher.resources.toggle-pinned', $resource) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="archive-{{ $resource->id }}" action="{{ route('teacher.resources.archive', $resource) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        <form id="delete-{{ $resource->id }}" action="{{ route('teacher.resources.destroy', $resource) }}" method="POST" style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-book fa-3x mb-3"></i>
                                        <br>No resources found. Create your first resource to get started.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($resources->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $resources->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('select[name="category"], select[name="type"], select[name="status"], select[name="visibility"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
