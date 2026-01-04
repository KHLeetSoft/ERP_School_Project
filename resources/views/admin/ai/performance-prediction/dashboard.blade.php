@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Performance Prediction - Dashboard</h5>
            <a href="{{ route('admin.ai.performance-prediction.index') }}" class="btn btn-sm btn-outline-primary">Predict New</a>
        </div>
        <div class="card-body">
            @php $list = session('performance.predictions', []); @endphp
            @if(empty($list))
                <div class="text-muted">No predictions stored for this session.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Score</th>
                                <th>Risk</th>
                                <th>Advice</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $i => $p)
                            <tr>
                                <td>{{ $i+1 }}</td>
                                <td>{{ $p['name'] ?? '-' }}</td>
                                <td>{{ $p['predicted_score'] ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ ($p['risk_level'] ?? 'low')==='high' ? 'bg-danger' : ((($p['risk_level'] ?? 'low')==='medium') ? 'bg-warning' : 'bg-success') }}">
                                        {{ ucfirst($p['risk_level'] ?? 'low') }}
                                    </span>
                                </td>
                                <td class="text-muted">{{ Str::limit($p['advice'] ?? '-', 80) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


