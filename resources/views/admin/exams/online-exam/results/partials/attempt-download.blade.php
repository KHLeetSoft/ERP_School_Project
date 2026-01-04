<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Result - {{ $attempt->student->name ?? 'Student' }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="mb-0">Online Exam Result</h4>
      <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">Print</button>
    </div>
    <div class="card">
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <div><strong>Student:</strong> {{ $attempt->student->name ?? 'N/A' }}</div>
            <div><strong>Email:</strong> {{ $attempt->student->email ?? 'N/A' }}</div>
          </div>
          <div class="col-md-6">
            <div><strong>Exam:</strong> {{ $attempt->onlineExam->title }}</div>
            <div><strong>Marks:</strong> {{ $attempt->total_marks_obtained }} / {{ $attempt->onlineExam->total_marks }}</div>
            <div><strong>Percentage:</strong> {{ number_format($attempt->percentage,1) }}%</div>
            <div><strong>Result:</strong> {{ $attempt->is_passed ? 'Passed' : 'Failed' }}</div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6"><strong>Started:</strong> {{ $attempt->started_at?->format('d M Y h:i A') }}</div>
          <div class="col-md-6"><strong>Submitted:</strong> {{ $attempt->submitted_at?->format('d M Y h:i A') ?? '-' }}</div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>


