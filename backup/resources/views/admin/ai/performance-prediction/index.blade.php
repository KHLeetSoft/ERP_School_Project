@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">AI Performance Prediction</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ai.performance-prediction.predict') }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Class</label>
                            <input type="text" name="class_name" class="form-control" value="{{ old('class_name', $input['class_name'] ?? '') }}" placeholder="e.g., Class 10 - A">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject', $input['subject'] ?? '') }}" placeholder="e.g., Mathematics">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Difficulty</label>
                            <select name="difficulty" class="form-select">
                                <option value="easy" {{ (old('difficulty', $input['difficulty'] ?? '')==='easy')?'selected':'' }}>Easy</option>
                                <option value="medium" {{ (old('difficulty', $input['difficulty'] ?? 'medium')==='medium')?'selected':'' }}>Medium</option>
                                <option value="hard" {{ (old('difficulty', $input['difficulty'] ?? '')==='hard')?'selected':'' }}>Hard</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Constraints, syllabus coverage, etc.">{{ old('notes', $input['notes'] ?? '') }}</textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Students (CSV lines: name,attendance%,avg_score)</label>
                            <textarea name="students" rows="8" class="form-control" placeholder="Riya,92,74
Arjun,80,65
Kavya,60,40
...">{{ old('students', $input['students'] ?? '') }}</textarea>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary"><i class="bx bx-bot"></i> Predict</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center" style="gap:.5rem;">
                        <h5 class="mb-0">Predictions</h5>
                        <a href="{{ route('admin.ai.performance-prediction.dashboard') }}" class="btn btn-sm btn-outline-secondary">Dashboard</a>
                    </div>
                    @if(!empty($predictions))
                        <span class="badge bg-info">{{ count($predictions) }} students</span>
                    @endif
                </div>
                <div class="card-body" style="max-height: 65vh; overflow:auto;">
                    @if(!empty($error))
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endif
                    @forelse(($predictions ?? []) as $p)
                        <div class="mb-2 p-2 border rounded">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $p['name'] ?? '-' }}</strong>
                                <span class="badge {{ ($p['risk_level'] ?? 'low')==='high' ? 'bg-danger' : ((($p['risk_level'] ?? 'low')==='medium') ? 'bg-warning' : 'bg-success') }}">
                                    {{ ucfirst($p['risk_level'] ?? 'low') }}
                                </span>
                            </div>
                            <div class="mt-1">Predicted Score: <strong>{{ $p['predicted_score'] ?? '-' }}</strong></div>
                            @if(!empty($p['advice']))
                                <div class="text-muted small">Advice: {{ $p['advice'] }}</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-muted">No predictions yet. Provide inputs and click Predict.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


