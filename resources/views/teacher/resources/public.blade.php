@extends('teacher.layout.app')

@section('title', 'Public Resources')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-globe"></i> Public Resources
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher.resources.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> My Resources
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('teacher.resources.public') }}" class="row g-3">
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
                                    <select name="sort" class="form-select">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                        <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control" placeholder="Search resources..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Resources Grid -->
                    <div class="row">
                        @forelse($resources as $resource)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 resource-card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        @if($resource->is_featured)
                                            <i class="fas fa-star text-warning me-2" title="Featured"></i>
                                        @endif
                                        <span class="badge" style="background-color: {{ $resource->category->color }}; color: white;">
                                            {{ $resource->category->name }}
                                        </span>
                                    </div>
                                    <span class="badge bg-{{ $resource->type_color }}">
                                        <i class="bx {{ $resource->type_icon }}"></i>
                                    </span>
                                </div>
                                
                                <div class="card-body">
                                    <h6 class="card-title">{{ $resource->title }}</h6>
                                    @if($resource->description)
                                    <p class="card-text text-muted small">{{ Str::limit($resource->description, 100) }}</p>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center text-muted small">
                                        <span>
                                            <i class="fas fa-user"></i> {{ $resource->teacher->name }}
                                        </span>
                                        <span>
                                            <i class="fas fa-calendar"></i> {{ $resource->created_at->format('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-muted small">
                                            <i class="fas fa-eye"></i> {{ $resource->view_count }}
                                            <i class="fas fa-download ms-2"></i> {{ $resource->download_count }}
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ route('teacher.resources.show', $resource) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($resource->file_path)
                                            <a href="{{ route('teacher.resources.download', $resource) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-globe fa-3x mb-3"></i>
                                <h5>No public resources found</h5>
                                <p>There are no public resources available at the moment.</p>
                            </div>
                        </div>
                        @endforelse
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

@push('styles')
<style>
.resource-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.resource-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.resource-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.resource-card .card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('select[name="category"], select[name="type"], select[name="sort"]').on('change', function() {
        $(this).closest('form').submit();
    });
});
</script>
@endpush
