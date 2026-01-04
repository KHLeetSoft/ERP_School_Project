<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ModalAttendanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'modal:attendance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate attendance modal for the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Create the attendance modal view
        $this->createAttendanceModal();
        
        $this->info('Attendance modal generated successfully!');
        
        return 0;
    }
    
    /**
     * Create the attendance modal view file
     *
     * @return void
     */
    protected function createAttendanceModal()
    {
        $viewPath = resource_path('views/admin/students/attendance/create.blade.php');
        
        // Check if the directory exists, if not create it
        $directory = dirname($viewPath);
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        
        // Create the modal content
        $content = <<<'EOT'
@extends('admin.layout.app')

@section('title', 'Take Attendance')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between">
            <h4 class="page-title">Take Attendance</h4>
            <a href="{{ route('admin.students.attendance.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Take Daily Attendance</h4>
                </div>
                <div class="card-body">
                    <form id="attendance-form">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class_id">Class</label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class_section_id">Section</label>
                                    <select class="form-select" id="class_section_id" name="class_section_id" required disabled>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" id="load-students" class="btn btn-primary">
                                    <i class="fas fa-sync"></i> Load Students
                                </button>
                            </div>
                        </div>
                    </form>

                    <div id="students-container" class="mt-4" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="15%">Roll No</th>
                                        <th width="30%">Student Name</th>
                                        <th width="20%">Attendance Status</th>
                                        <th width="30%">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody id="students-list">
                                    <!-- Students will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button type="button" id="save-attendance" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Attendance
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Load sections when class is selected
        $('#class_id').change(function() {
            const classId = $(this).val();
            if (classId) {
                $.ajax({
                    url: "{{ route('admin.students.attendance.get-sections-by-class') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        class_id: classId
                    },
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Select Section</option>';
                            response.data.forEach(function(section) {
                                options += `<option value="${section.id}">${section.name}</option>`;
                            });
                            $('#class_section_id').html(options).prop('disabled', false);
                        } else {
                            toastr.error('Failed to load sections');
                        }
                    },
                    error: function() {
                        toastr.error('Failed to load sections');
                    }
                });
            } else {
                $('#class_section_id').html('<option value="">Select Section</option>').prop('disabled', true);
            }
        });

        // Load students when button is clicked
        $('#load-students').click(function() {
            const classSectionId = $('#class_section_id').val();
            const date = $('#date').val();

            if (!classSectionId || !date) {
                toastr.error('Please select class, section and date');
                return;
            }

            $.ajax({
                url: "{{ route('admin.students.attendance.get-students-by-class-section') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    class_section_id: classSectionId,
                    date: date
                },
                success: function(response) {
                    if (response.success) {
                        renderStudents(response.data);
                        $('#students-container').show();
                    } else {
                        toastr.error('Failed to load students');
                    }
                },
                error: function() {
                    toastr.error('Failed to load students');
                }
            });
        });

        // Render students in the table
        function renderStudents(students) {
            let html = '';
            students.forEach(function(student, index) {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${student.roll_number}</td>
                        <td>${student.name}</td>
                        <td>
                            <input type="hidden" name="students[${index}][id]" value="${student.id}">
                            <select class="form-select" name="students[${index}][status]">
                                <option value="Present" ${student.status === 'Present' ? 'selected' : ''}>Present</option>
                                <option value="Absent" ${student.status === 'Absent' ? 'selected' : ''}>Absent</option>
                                <option value="Late" ${student.status === 'Late' ? 'selected' : ''}>Late</option>
                                <option value="Excused" ${student.status === 'Excused' ? 'selected' : ''}>Excused</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="students[${index}][remarks]" value="${student.remarks || ''}" placeholder="Optional remarks">
                        </td>
                    </tr>
                `;
            });
            $('#students-list').html(html);
        }

        // Save attendance
        $('#save-attendance').click(function() {
            const classSectionId = $('#class_section_id').val();
            const date = $('#date').val();
            
            // Collect student attendance data
            const students = [];
            $('#students-list tr').each(function() {
                const studentId = $(this).find('input[name^="students"][name$="[id]"]').val();
                const status = $(this).find('select[name^="students"][name$="[status]"]').val();
                const remarks = $(this).find('input[name^="students"][name$="[remarks]"]').val();
                
                students.push({
                    id: studentId,
                    status: status,
                    remarks: remarks
                });
            });

            $.ajax({
                url: "{{ route('admin.students.attendance.save') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    class_section_id: classSectionId,
                    date: date,
                    students: students
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('Attendance saved successfully');
                        setTimeout(function() {
                            window.location.href = "{{ route('admin.students.attendance.index') }}";
                        }, 1000);
                    } else {
                        toastr.error(response.message || 'Failed to save attendance');
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    toastr.error(response?.message || 'Failed to save attendance');
                }
            });
        });
    });
</script>
@endsection
EOT;
        
        // Write the content to the file
        File::put($viewPath, $content);
        
        $this->info("Created: {$viewPath}");
    }
}