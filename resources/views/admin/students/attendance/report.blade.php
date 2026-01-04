@extends('admin.layout.app')

@section('title', 'Attendance Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monthly Attendance Report</h4>
                </div>
                <div class="card-body">
                    <form id="report-form">
                        <div class="row mb-3">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="class_section_id">Section</label>
                                    <select class="form-select" id="class_section_id" name="class_section_id" required disabled>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <select class="form-select" id="month" name="month" required>
                                        <option value="1" {{ date('n') == 1 ? 'selected' : '' }}>January</option>
                                        <option value="2" {{ date('n') == 2 ? 'selected' : '' }}>February</option>
                                        <option value="3" {{ date('n') == 3 ? 'selected' : '' }}>March</option>
                                        <option value="4" {{ date('n') == 4 ? 'selected' : '' }}>April</option>
                                        <option value="5" {{ date('n') == 5 ? 'selected' : '' }}>May</option>
                                        <option value="6" {{ date('n') == 6 ? 'selected' : '' }}>June</option>
                                        <option value="7" {{ date('n') == 7 ? 'selected' : '' }}>July</option>
                                        <option value="8" {{ date('n') == 8 ? 'selected' : '' }}>August</option>
                                        <option value="9" {{ date('n') == 9 ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ date('n') == 10 ? 'selected' : '' }}>October</option>
                                        <option value="11" {{ date('n') == 11 ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ date('n') == 12 ? 'selected' : '' }}>December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <select class="form-select" id="year" name="year" required>
                                        @for($i = date('Y'); $i >= date('Y')-5; $i--)
                                            <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="button" id="generate-report" class="btn btn-primary">Generate Report</button>
                            </div>
                        </div>
                    </form>

                    <div id="report-container" class="mt-4" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 id="report-title">Attendance Report</h5>
                            <button type="button" id="print-report" class="btn btn-info"><i class="bx bx-printer"></i> Print</button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" id="report-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="align-middle">Roll No</th>
                                        <th rowspan="2" class="align-middle">Student Name</th>
                                        <th colspan="31" class="text-center" id="month-header">Month</th>
                                        <th colspan="4" class="text-center">Summary</th>
                                    </tr>
                                    <tr id="days-header">
                                        <!-- Days will be added dynamically -->
                                        <th>P</th>
                                        <th>A</th>
                                        <th>L</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody id="report-data">
                                    <!-- Report data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Legend:</h6>
                                    <div class="d-flex flex-wrap">
                                        <span class="badge bg-success me-2 mb-2">P: Present</span>
                                        <span class="badge bg-danger me-2 mb-2">A: Absent</span>
                                        <span class="badge bg-warning me-2 mb-2">L: Late</span>
                                        <span class="badge bg-info me-2 mb-2">E: Excused</span>
                                        <span class="badge bg-secondary me-2 mb-2">W: Weekend</span>
                                        <span class="badge bg-light text-dark me-2 mb-2">N/A: Not Available</span>
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

        // Generate report
        $('#generate-report').click(function() {
            const classSectionId = $('#class_section_id').val();
            const month = $('#month').val();
            const year = $('#year').val();

            if (!classSectionId || !month || !year) {
                toastr.error('Please select all required fields');
                return;
            }

            $.ajax({
                url: "{{ route('admin.attendance.report-data') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    class_section_id: classSectionId,
                    month: month,
                    year: year
                },
                success: function(response) {
                    if (response.success) {
                        renderReport(response.data);
                        $('#report-container').show();
                    } else {
                        toastr.error('Failed to generate report');
                    }
                },
                error: function() {
                    toastr.error('Failed to generate report');
                }
            });
        });

        // Render report
        function renderReport(data) {
            // Set month header
            $('#month-header').text(`${data.month_name} ${data.year}`);
            
            // Generate days header
            let daysHeader = '';
            data.days.forEach(function(day) {
                const dayClass = day.is_weekend ? 'bg-light text-secondary' : '';
                daysHeader += `<th class="${dayClass}">${day.day}</th>`;
            });
            daysHeader += '<th>P</th><th>A</th><th>L</th><th>%</th>';
            $('#days-header').html(daysHeader);
            
            // Generate report data
            let reportData = '';
            data.students.forEach(function(student) {
                reportData += `<tr>
                    <td>${student.roll_number}</td>
                    <td>
                        <a href="{{ url('admin/attendance/student') }}/${student.student_id}">${student.name}</a>
                    </td>`;
                
                // Add daily status
                student.daily_status.forEach(function(day) {
                    let statusClass = '';
                    let statusText = '';
                    
                    switch(day.status) {
                        case 'Present':
                            statusClass = 'bg-success text-white';
                            statusText = 'P';
                            break;
                        case 'Absent':
                            statusClass = 'bg-danger text-white';
                            statusText = 'A';
                            break;
                        case 'Late':
                            statusClass = 'bg-warning';
                            statusText = 'L';
                            break;
                        case 'Excused':
                            statusClass = 'bg-info text-white';
                            statusText = 'E';
                            break;
                        case 'Weekend':
                            statusClass = 'bg-secondary text-white';
                            statusText = 'W';
                            break;
                        default:
                            statusClass = '';
                            statusText = '-';
                    }
                    
                    if (day.is_weekend) {
                        statusClass += ' bg-light text-secondary';
                    }
                    
                    reportData += `<td class="${statusClass}" title="${day.status}${day.remarks ? ': ' + day.remarks : ''}">${statusText}</td>`;
                });
                
                // Add summary
                reportData += `
                    <td>${student.summary.present}</td>
                    <td>${student.summary.absent}</td>
                    <td>${student.summary.late}</td>
                    <td>${student.summary.attendance_percentage}%</td>
                </tr>`;
            });
            
            $('#report-data').html(reportData);
            $('#report-title').text(`Attendance Report - ${data.month_name} ${data.year}`);
        }

        // Print report
        $('#print-report').click(function() {
            const printContents = document.getElementById('report-container').innerHTML;
            const originalContents = document.body.innerHTML;
            
            document.body.innerHTML = `
                <div class="container mt-3">
                    <h3 class="text-center mb-4">Student Attendance Report</h3>
                    ${printContents}
                </div>
            `;
            
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    });
</script>
@endsection