@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Attendance Reports Dashboard</h6>
        <a href="{{ route('admin.attendance.reports.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="border rounded p-3 text-center">
                <div class="text-muted text-uppercase small">Staff Present (7d)</div>
                <div class="fs-4 fw-semibold">{{ $totals['staff_present'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded p-3 text-center">
                <div class="text-muted text-uppercase small">Staff Absent (7d)</div>
                <div class="fs-4 fw-semibold">{{ $totals['staff_absent'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded p-3 text-center">
                <div class="text-muted text-uppercase small">Student Present (7d)</div>
                <div class="fs-4 fw-semibold">{{ $totals['student_present'] }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded p-3 text-center">
                <div class="text-muted text-uppercase small">Student Absent (7d)</div>
                <div class="fs-4 fw-semibold">{{ $totals['student_absent'] }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">7-Day Trends</div>
                <div class="card-body"><canvas id="trendChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">Attendance Rate (Staff vs Students)</div>
                <div class="card-body"><canvas id="rateChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header">Yesterday Split</div>
                <div class="card-body"><canvas id="donutChart"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">Stacked Present vs Absent (Daily)</div>
                <div class="card-body"><canvas id="stackedChart"></canvas></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function(){
        const labels = {!! json_encode($labels) !!};
        const staffP = {!! json_encode($staffPresent) !!};
        const studP = {!! json_encode($studentPresent) !!};
        const staffA = {!! json_encode($staffAbsent) !!};
        const studA = {!! json_encode($studentAbsent) !!};
        const staffRate = {!! json_encode($staffRate) !!};
        const studentRate = {!! json_encode($studentRate) !!};
        const ctx = document.getElementById('trendChart');
        if (!ctx) return;
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    { label: 'Staff Present', data: staffP, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.2)' },
                    { label: 'Student Present', data: studP, borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.2)' },
                    { label: 'Staff Absent', data: staffA, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.2)' },
                    { label: 'Student Absent', data: studA, borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.2)' },
                ]
            },
            options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero: true } } }
        });

        const rate = document.getElementById('rateChart');
        if (rate) {
            new Chart(rate, {
                type: 'line',
                data: { labels, datasets: [
                    { label: 'Staff %', data: staffRate, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)' },
                    { label: 'Student %', data: studentRate, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)' },
                ]},
                options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true, max:100 } } }
            });
        }

        const donut = document.getElementById('donutChart');
        if (donut) {
            new Chart(donut, {
                type: 'doughnut',
                data: {
                    labels: ['Staff Present','Staff Absent','Student Present','Student Absent'],
                    datasets: [{ data: [
                        {{ (int)($lastStaffPresent ?? 0) }},
                        {{ (int)($lastStaffAbsent ?? 0) }},
                        {{ (int)($lastStudentPresent ?? 0) }},
                        {{ (int)($lastStudentAbsent ?? 0) }}
                    ], backgroundColor: ['#22c55e','#ef4444','#3b82f6','#f59e0b'] }]
                },
                options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } } }
            });
        }

        const stacked = document.getElementById('stackedChart');
        if (stacked) {
            new Chart(stacked, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'Staff Present', data: staffP, backgroundColor: '#22c55e' },
                        { label: 'Staff Absent', data: staffA, backgroundColor: '#ef4444' },
                        { label: 'Student Present', data: studP, backgroundColor: '#3b82f6' },
                        { label: 'Student Absent', data: studA, backgroundColor: '#f59e0b' },
                    ]
                },
                options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { x: { stacked:true }, y: { stacked:true, beginAtZero:true } } }
            });
        }
    })();
</script>
@endsection


