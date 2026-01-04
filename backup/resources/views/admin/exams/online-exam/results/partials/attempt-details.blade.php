<div>
  <div class="mb-2">
    <strong>Student:</strong> {{ $attempt->student->name ?? 'N/A' }} ({{ $attempt->student->email ?? 'N/A' }})
  </div>
  <div class="mb-2">
    <strong>Exam:</strong> {{ $attempt->onlineExam->title }}
  </div>
  <div class="row mb-3">
    <div class="col-md-3"><strong>Attempt #</strong><br>{{ $attempt->attempt_number }}</div>
    <div class="col-md-3"><strong>Marks</strong><br>{{ $attempt->total_marks_obtained }} / {{ $attempt->onlineExam->total_marks }}</div>
    <div class="col-md-3"><strong>Percentage</strong><br>{{ number_format($attempt->percentage,1) }}%</div>
    <div class="col-md-3"><strong>Result</strong><br>
      @if($attempt->is_passed)
        <span class="badge bg-success">Passed</span>
      @else
        <span class="badge bg-danger">Failed</span>
      @endif
    </div>
  </div>

  <div class="mb-2"><strong>Started:</strong> {{ $attempt->started_at?->format('d M Y h:i A') }}</div>
  <div class="mb-2"><strong>Submitted:</strong> {{ $attempt->submitted_at?->format('d M Y h:i A') ?? '-' }}</div>
  <div class="mb-3"><strong>Time Taken:</strong> {{ $attempt->time_taken_minutes ? $attempt->time_taken_minutes.' min' : '-' }}</div>

  <hr>
  <h6>Answers</h6>
  @php $answers = $attempt->answers ?? []; @endphp
  @if(!empty($answers))
    <ul class="list-group">
      @foreach($answers as $questionId => $ans)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <span>Q#{{ $questionId }}</span>
          <span>{{ is_array($ans) ? json_encode($ans) : $ans }}</span>
        </li>
      @endforeach
    </ul>
  @else
    <div class="text-muted">No answers recorded.</div>
  @endif
</div>


