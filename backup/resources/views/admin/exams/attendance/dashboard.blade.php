@extends('admin.layout.app')

@section('content')
<div class="container-fluid">

    <!-- Stats Cards -->
    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-success border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total</h6>
                        <h3 class="mb-0">{{ $totals['all'] ?? 0 }}</h3>
                    </div>
                    <div class="text-success fs-2"><i class="bx bx-collection"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Present</h6>
                        <h3 class="mb-0">{{ $totals['present'] ?? 0 }}</h3>
                    </div>
                    <div class="text-primary fs-2"><i class="bx bx-user-check"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-danger border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Absent</h6>
                        <h3 class="mb-0">{{ $totals['absent'] ?? 0 }}</h3>
                    </div>
                    <div class="text-danger fs-2"><i class="bx bx-user-x"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-warning border-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Late</h6>
                        <h3 class="mb-0">{{ $totals['late'] ?? 0 }}</h3>
                    </div>
                    <div class="text-warning fs-2"><i class="bx bx-time-five"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-4 g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Attendance Distribution</strong></div>
                <div class="card-body">
                    <div id="attendanceChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Daily Attendance Trend</strong></div>
                <div class="card-body">
                    <div id="trendChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- More Diagrams -->
    <div class="row mt-4 g-3">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Class-wise Attendance</strong></div>
                <div class="card-body">
                    <div id="classChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Overall Attendance %</strong></div>
                <div class="card-body">
                    <div id="radialChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light"><strong>Attendance Performance</strong></div>
                <div class="card-body">
                    <div id="areaChart" style="height:300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Table -->
    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-light"><strong>Recent Attendance</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent as $r)
                            <tr>
                                <td>{{ optional($r->exam)->title }}</td>
                                <td>{{ $r->class_name }}</td>
                                <td>{{ $r->student_name }}</td>
                                <td>{{ $r->subject_name }}</td>
                                <td>{{ optional($r->exam_date)->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge 
                                        @if($r->attendance_status=='present') bg-success
                                        @elseif($r->attendance_status=='absent') bg-danger
                                        @elseif($r->attendance_status=='late') bg-warning text-dark
                                        @else bg-secondary @endif">
                                        {{ ucfirst($r->attendance_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function(){

    // Donut Chart - Attendance Distribution
    new ApexCharts(document.querySelector("#attendanceChart"), {
        chart: { type: 'donut', height: 300 },
        labels: ['Present','Absent','Late'],
        series: [{{ $totals['present'] ?? 0 }}, {{ $totals['absent'] ?? 0 }}, {{ $totals['late'] ?? 0 }}],
        colors: ['#198754','#dc3545','#ffc107'],
        legend: { position: 'bottom' }
    }).render();

    // Line Chart - Attendance Trend (dummy data)
    new ApexCharts(document.querySelector("#trendChart"), {
        chart: { type: 'line', height: 300 },
        series: [{
            name: 'Present',
            data: [10,15,20,18,22,25,28]
        },{
            name: 'Absent',
            data: [2,3,1,4,2,1,0]
        },{
            name: 'Late',
            data: [1,2,1,3,1,2,1]
        }],
        xaxis: { categories: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] },
        colors: ['#198754','#dc3545','#ffc107'],
        dataLabels: { enabled: true }
    }).render();

    // Bar Chart - Class wise Attendance (dummy data)
    new ApexCharts(document.querySelector("#classChart"), {
        chart: { type: 'bar', height: 300 },
        series: [{
            name: 'Present',
            data: [30,40,35,50]
        },{
            name: 'Absent',
            data: [5,7,3,6]
        }],
        xaxis: { categories: ['Class 6','Class 7','Class 8','Class 9'] },
        colors: ['#198754','#dc3545']
    }).render();

    // Radial Chart - Overall %
    new ApexCharts(document.querySelector("#radialChart"), {
        chart: { type: 'radialBar', height: 300 },
        series: [ {{ $totals['all'] > 0 ? round(($totals['present'] / $totals['all']) * 100,1) : 0 }} ],
        labels: ['Present %'],
        colors: ['#0d6efd']
    }).render();

    // Area Chart - Performance Trend
    new ApexCharts(document.querySelector("#areaChart"), {
        chart: { type: 'area', height: 300 },
        series: [{
            name: 'Attendance Rate',
            data: [70,75,80,78,85,90,88]
        }],
        xaxis: { categories: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] },
        colors: ['#20c997']
    }).render();

});
</script>
@endsection
