@extends('admin.layout.app')
@section('content')
<div class="container py-4">
    <h1 class="mb-4">📊 Coverage Dashboard</h1>

    <!-- Top Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6>Total</h6>
                    <h3 class="text-primary">{{ $total }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6>Pending</h6>
                    <h3 class="text-warning">{{ $pending }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <h6>Completed</h6>
                    <h3 class="text-success">{{ $completed }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Class-wise Chart</h6>
                    <canvas id="classChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="mb-3">Subject-wise Chart</h6>
                    <canvas id="subjectChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Class-wise Status Table -->
    <h3>Class-wise Status</h3>
    <table class="table table-bordered">
        <thead>
            <tr><th>Class</th><th>Pending</th><th>Completed</th></tr>
        </thead>
        <tbody>
        @foreach($classStatus as $class => $status)
            <tr>
                <td>{{ $class }}</td>
                <td>{{ $status['pending'] }}</td>
                <td>{{ $status['completed'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Subject-wise Status Table -->
    <h3>Subject-wise Status</h3>
    <table class="table table-bordered">
        <thead>
            <tr><th>Subject</th><th>Pending</th><th>Completed</th></tr>
        </thead>
        <tbody>
        @foreach($subjectStatus as $subject => $status)
            <tr>
                <td>{{ $subject }}</td>
                <td>{{ $status['pending'] }}</td>
                <td>{{ $status['completed'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // === Class Wise Bar Chart ===
    new Chart(document.getElementById('classChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($classStatus)) !!},
            datasets: [
                {
                    label: 'Pending',
                    data: {!! json_encode(array_column($classStatus, 'pending')) !!},
                    backgroundColor: 'rgba(255, 206, 86, 0.7)'
                },
                {
                    label: 'Completed',
                    data: {!! json_encode(array_column($classStatus, 'completed')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.7)'
                }
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'top' } } }
    });

    // === Subject Wise Doughnut Chart ===
    new Chart(document.getElementById('subjectChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($subjectStatus)) !!},
            datasets: [{
                data: {!! json_encode(array_map(fn($s) => $s['completed'] + $s['pending'], $subjectStatus)) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ]
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection
