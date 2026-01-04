@extends('teacher.layout.app')

@section('title', 'Resource Statistics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Resource Statistics
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('teacher.resources.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Resources
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Overview Stats -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $stats['total_resources'] }}</h3>
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
                                    <h3>{{ $stats['published_resources'] }}</h3>
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
                                    <h3>{{ $stats['total_downloads'] }}</h3>
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
                                    <h3>{{ $stats['total_views'] }}</h3>
                                    <p>Total Views</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Resources by Category -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Resources by Category</h5>
                                </div>
                                <div class="card-body">
                                    @if($categoryStats->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    <th>Count</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($categoryStats as $category)
                                                <tr>
                                                    <td>
                                                        <span class="badge" style="background-color: {{ $category->category->color }}; color: white;">
                                                            {{ $category->category->name }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $category->count }}</td>
                                                    <td>
                                                        @php
                                                            $percentage = $stats['total_resources'] > 0 ? ($category->count / $stats['total_resources']) * 100 : 0;
                                                        @endphp
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                                {{ number_format($percentage, 1) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted text-center">No category data available</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Resources by Type -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Resources by Type</h5>
                                </div>
                                <div class="card-body">
                                    @if($typeStats->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Count</th>
                                                    <th>Percentage</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($typeStats as $type)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-{{ $type->type === 'file' ? 'primary' : ($type->type === 'link' ? 'info' : ($type->type === 'video' ? 'danger' : 'secondary')) }}">
                                                            <i class="bx {{ $type->type === 'file' ? 'bx-file' : ($type->type === 'link' ? 'bx-link' : ($type->type === 'video' ? 'bx-video' : 'bx-file')) }}"></i>
                                                            {{ ucfirst($type->type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $type->count }}</td>
                                                    <td>
                                                        @php
                                                            $percentage = $stats['total_resources'] > 0 ? ($type->count / $stats['total_resources']) * 100 : 0;
                                                        @endphp
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%">
                                                                {{ number_format($percentage, 1) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted text-center">No type data available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Monthly Resource Creation</h5>
                                </div>
                                <div class="card-body">
                                    @if($monthlyStats->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Month</th>
                                                    <th>Resources Created</th>
                                                    <th>Chart</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $maxCount = $monthlyStats->max('count');
                                                @endphp
                                                @foreach($monthlyStats as $month)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month->month)->format('F Y') }}</td>
                                                    <td>{{ $month->count }}</td>
                                                    <td>
                                                        @php
                                                            $width = $maxCount > 0 ? ($month->count / $maxCount) * 100 : 0;
                                                        @endphp
                                                        <div class="progress" style="height: 20px;">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $width }}%">
                                                                {{ $month->count }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <p class="text-muted text-center">No monthly data available</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Stats -->
                    <div class="row mt-4">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Status Distribution</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Published</span>
                                        <span class="badge badge-success">{{ $stats['published_resources'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Draft</span>
                                        <span class="badge badge-warning">{{ $stats['draft_resources'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Archived</span>
                                        <span class="badge badge-secondary">{{ $stats['archived_resources'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Special Resources</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Featured</span>
                                        <span class="badge badge-warning">{{ $stats['featured_resources'] }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Pinned</span>
                                        <span class="badge badge-info">{{ $stats['pinned_resources'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Engagement</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Avg Views/Resource</span>
                                        <span class="badge badge-primary">
                                            {{ $stats['total_resources'] > 0 ? number_format($stats['total_views'] / $stats['total_resources'], 1) : 0 }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Avg Downloads/Resource</span>
                                        <span class="badge badge-success">
                                            {{ $stats['total_resources'] > 0 ? number_format($stats['total_downloads'] / $stats['total_resources'], 1) : 0 }}
                                        </span>
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
