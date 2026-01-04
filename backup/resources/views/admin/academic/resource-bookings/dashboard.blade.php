@extends('admin.layout.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Resource Booking Dashboard</h3>
        <a href="{{ route('admin.academic.resource-bookings.create') }}" class="btn btn-primary">New Booking</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-2">
                <h6 class="fw-bold">Total</h6>
                <p class="display-6 mb-0">{{ array_sum(($statusCounts ?? [])->toArray()) }}</p>
            </div>
        </div>
        @foreach(['pending'=>'warning','approved'=>'success','rejected'=>'danger','cancelled'=>'secondary'] as $label=>$color)
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-2">
                <h6 class="fw-bold text-capitalize">{{ $label }}</h6>
                <p class="display-6 mb-0 text-{{ $color }}">{{ ($statusCounts[$label] ?? 0) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-sm-12"><canvas id="statusPie" height="180"></canvas></div>
        <div class="col-md-6 col-sm-12"><canvas id="monthlyBar" height="180"></canvas></div>
        <div class="col-md-6 col-sm-12"><canvas id="resourceTypeDonut" height="180"></canvas></div>
        <div class="col-md-6 col-sm-12"><canvas id="dailyLine" height="180"></canvas></div>
        <div class="col-md-12 col-sm-12"><canvas id="topResourcesBar" height="200"></canvas></div>
    </div>

    <div class="card">
        <div class="card-header">Upcoming Bookings</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Resource</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->title }}</td>
                            <td>{{ $booking->resource_type }} {{ $booking->resource_name ? '(' . $booking->resource_name . ')' : '' }}</td>
                            <td>{{ $booking->start_time->format('Y-m-d H:i') }}</td>
                            <td>{{ $booking->end_time->format('Y-m-d H:i') }}</td>
                            <td>{{ ucfirst($booking->status) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center p-3">No upcoming bookings.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Pie Chart
    const statusData = {
        labels: ['Pending', 'Approved', 'Rejected', 'Cancelled'],
        datasets: [{
            data: [
                {{ (int) ($statusCounts['pending'] ?? 0) }},
                {{ (int) ($statusCounts['approved'] ?? 0) }},
                {{ (int) ($statusCounts['rejected'] ?? 0) }},
                {{ (int) ($statusCounts['cancelled'] ?? 0) }}
            ],
            backgroundColor: ['#ffc107','#198754','#dc3545','#6c757d']
        }]
    };
    new Chart(document.getElementById('statusPie'), { type: 'pie', data: statusData, options: { responsive: true }});

    // Monthly Bar Chart
    const months = {!! json_encode($months ?? []) !!};
    const monthlySeries = {!! json_encode($monthlySeries ?? []) !!};
    new Chart(document.getElementById('monthlyBar'), {
        type: 'bar',
        data: { labels: months, datasets: [{ label: 'Bookings', data: monthlySeries, backgroundColor: '#0d6efd' }] },
        options: { responsive: true, plugins: { legend: { position: 'top' }}}
    });

    // Resource Type Donut
    const rtLabels = {!! json_encode(($resourceTypeCounts ?? collect())->keys()->toArray()) !!};
    const rtData = {!! json_encode(($resourceTypeCounts ?? collect())->values()->toArray()) !!};
    new Chart(document.getElementById('resourceTypeDonut'), {
        type: 'doughnut',
        data: { labels: rtLabels, datasets: [{ data: rtData, backgroundColor: ['#0dcaf0','#6610f2','#198754','#fd7e14','#dc3545','#6f42c1'] }] },
        options: { responsive: true }
    });

    // Last 30 Days Line
    const dailyLabels = {!! json_encode($dailyLabels ?? []) !!};
    const dailySeries = {!! json_encode($dailySeries ?? []) !!};
    new Chart(document.getElementById('dailyLine'), {
        type: 'line',
        data: { labels: dailyLabels, datasets: [{ label: 'Bookings', data: dailySeries, borderColor: '#198754', fill: false, tension: 0.3 }] },
        options: { responsive: true }
    });

    // Top Resources Bar
    const trLabels = {!! json_encode($topResourceLabels ?? []) !!};
    const trSeries = {!! json_encode($topResourceSeries ?? []) !!};
    new Chart(document.getElementById('topResourcesBar'), {
        type: 'bar',
        data: { labels: trLabels, datasets: [{ label: 'Bookings', data: trSeries, backgroundColor: '#ffc107' }] },
        options: { responsive: true, plugins: { legend: { position: 'top' }}, indexAxis: 'y' }
    });
</script>
@endsection


