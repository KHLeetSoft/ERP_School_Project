@extends('admin.layout.app')

@section('content')
<<div class="container-fluid py-3">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h4 class="mb-0">Add Student Result</h4>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.students.results.store') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label for="student_id" class="form-label">👨‍🎓 Student <span class="text-danger">*</span></label>
              <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                <option value="">-- Select Student --</option>
                @foreach ($students as $student)
                  <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                    {{ $student->full_name }}
                  </option>
                @endforeach
              </select>
              @error('student_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="class_id" class="form-label">🏫 Class <span class="text-danger">*</span></label>
              <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                <option value="">-- Select Class --</option>
                @foreach($classes ?? [] as $class)
                  <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}
                  </option>
                @endforeach
              </select>
              @error('class_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="month" class="form-label">📅 Month <span class="text-danger">*</span></label>
              <select name="month" id="month" class="form-select @error('month') is-invalid @enderror" required>
                <option value="">-- Select Month --</option>
                @foreach ($months  ?? [] as $month)
                  <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>
                    {{ $month }}
                  </option>
                @endforeach
              </select>
              @error('month')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="subject_id" class="form-label">📘 Subject <span class="text-danger">*</span></label>
              <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                <option value="">-- Select Subject --</option>
                @foreach ($subjects ?? [] as $subject)
                  <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                    {{ $subject->subject_name }}
                  </option>
                @endforeach
              </select>
              @error('subject_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="marks_obtained" class="form-label">✍️ Marks Obtained <span class="text-danger">*</span></label>
              <input type="number" name="marks_obtained" id="marks_obtained" min="0" class="form-control @error('marks_obtained') is-invalid @enderror" value="{{ old('marks_obtained') }}" required>
              @error('marks_obtained')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="total_marks" class="form-label">📊 Total Marks <span class="text-danger">*</span></label>
              <input type="number" name="total_marks" id="total_marks" min="0" class="form-control @error('total_marks') is-invalid @enderror" value="{{ old('total_marks') }}" required>
              @error('total_marks')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="grade" class="form-label">🎓 Grade</label>
              <input type="text" name="grade" id="grade" class="form-control @error('grade') is-invalid @enderror" value="{{ old('grade') }}">
              @error('grade')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="exam_date" class="form-label">🗓️ Exam Date <span class="text-danger">*</span></label>
              <input type="date" name="exam_date" id="exam_date" class="form-control @error('exam_date') is-invalid @enderror" value="{{ old('exam_date') }}" required>
              @error('exam_date')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save Result</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection