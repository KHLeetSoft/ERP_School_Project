@extends('teacher.layout.app')

@section('title', $resource->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">
                                @if($resource->is_pinned)
                                    <i class="fas fa-thumbtack text-warning" title="Pinned"></i>
                                @endif
                                @if($resource->is_featured)
                                    <i class="fas fa-star text-warning" title="Featured"></i>
                                @endif
                                {{ $resource->title }}
                            </h3>
                            <p class="text-muted mb-0">
                                <span class="badge" style="background-color: {{ $resource->category->color }}; color: white;">
                                    {{ $resource->category->name }}
                                </span>
                                <span class="badge bg-{{ $resource->type_color }} ms-2">
                                    <i class="bx {{ $resource->type_icon }}"></i> {{ ucfirst($resource->type) }}
                                </span>
                            </p>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('teacher.resources.edit', $resource) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @if($resource->file_path)
                            <a href="{{ route('teacher.resources.download', $resource) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-download"></i> Download
                            </a>
                            @endif
                            <a href="{{ route('teacher.resources.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Resource Content -->
                            <div class="mb-4">
                                @if($resource->description)
                                <h5>Description</h5>
                                <p class="text-muted">{{ $resource->description }}</p>
                                @endif

                                @if($resource->type === 'link' && $resource->external_url)
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-external-link-alt"></i> External Link</h6>
                                    <a href="{{ $resource->external_url }}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt"></i> Visit Link
                                    </a>
                                </div>
                                @elseif($resource->type === 'text' && $resource->content)
                                <div class="content-section">
                                    <h5>Content</h5>
                                    <div class="border p-3 rounded bg-light">
                                        {!! nl2br(e($resource->content)) !!}
                                    </div>
                                </div>
                                @elseif($resource->file_path)
                                <div class="file-section">
                                    <h5>File Information</h5>
                                    <div class="border p-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>File Name:</strong> {{ $resource->file_name }}<br>
                                                <strong>File Size:</strong> {{ $resource->file_size_formatted }}<br>
                                                <strong>File Type:</strong> {{ strtoupper($resource->file_extension) }}
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <a href="{{ route('teacher.resources.download', $resource) }}" class="btn btn-success">
                                                    <i class="fas fa-download"></i> Download File
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Metadata -->
                            @if($resource->metadata)
                            <div class="mb-4">
                                <h5>Additional Information</h5>
                                <div class="border p-3 rounded bg-light">
                                    <pre class="mb-0">{{ json_encode($resource->metadata, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Resource Details -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Resource Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Status:</strong></td>
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
                                        </tr>
                                        <tr>
                                            <td><strong>Visibility:</strong></td>
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
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $resource->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        @if($resource->published_at)
                                        <tr>
                                            <td><strong>Published:</strong></td>
                                            <td>{{ $resource->published_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td><strong>Views:</strong></td>
                                            <td>{{ $resource->view_count }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Downloads:</strong></td>
                                            <td>{{ $resource->download_count }}</td>
                                        </tr>
                                        @if($resource->rating)
                                        <tr>
                                            <td><strong>Rating:</strong></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $resource->rating)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2">({{ $resource->rating }}/5)</span>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        @if($resource->status === 'published')
                                            <form action="{{ route('teacher.resources.unpublish', $resource) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm w-100">
                                                    <i class="fas fa-eye-slash"></i> Unpublish
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('teacher.resources.publish', $resource) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm w-100">
                                                    <i class="fas fa-eye"></i> Publish
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('teacher.resources.toggle-featured', $resource) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $resource->is_featured ? 'warning' : 'outline-warning' }} btn-sm w-100">
                                                <i class="fas fa-star"></i> {{ $resource->is_featured ? 'Remove from Featured' : 'Add to Featured' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('teacher.resources.toggle-pinned', $resource) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $resource->is_pinned ? 'warning' : 'outline-warning' }} btn-sm w-100">
                                                <i class="fas fa-thumbtack"></i> {{ $resource->is_pinned ? 'Unpin' : 'Pin' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('teacher.resources.archive', $resource) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary btn-sm w-100">
                                                <i class="fas fa-archive"></i> Archive
                                            </button>
                                        </form>

                                        <form action="{{ route('teacher.resources.destroy', $resource) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this resource? This action cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
