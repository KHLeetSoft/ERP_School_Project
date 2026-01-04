@extends('accountant.layout.app')

@section('title', 'Reports')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Financial Reports</h1>
    </div>

    <!-- Monthly Collection Chart -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Collection</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Summary</h6>
                </div>
                <div class="card-body">
                    @if(isset($monthlyData) && count($monthlyData) > 0)
                        @foreach($monthlyData as $data)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ date('F', mktime(0, 0, 0, $data->month, 1)) }}</span>
                                <span class="font-weight-bold">₹{{ number_format($data->total, 2) }}</span>
                            </div>
                            <div class="progress mb-2" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ ($data->total / $monthlyData->max('total')) * 100 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No data available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Class-wise Collection -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Class-wise Fee Collection</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Total Fees</th>
                                    <th>Paid Fees</th>
                                    <th>Pending Fees</th>
                                    <th>Collection Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($classWiseData as $data)
                                <tr>
                                    <td>{{ $data->class_name ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($data->total_fees, 2) }}</td>
                                    <td>₹{{ number_format($data->paid_fees, 2) }}</td>
                                    <td>₹{{ number_format($data->total_fees - $data->paid_fees, 2) }}</td>
                                    <td>
                                        @php
                                            $rate = $data->total_fees > 0 ? ($data->paid_fees / $data->total_fees) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $rate }}%">
                                                {{ number_format($rate, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Collection Chart
const monthlyData = @json($monthlyData ?? []);
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
const monthlyLabels = [];
const monthlyAmounts = [];

// Prepare data for chart
for (let i = 1; i <= 12; i++) {
    monthlyLabels.push(months[i-1]);
    const monthData = monthlyData.find(data => data.month == i);
    monthlyAmounts.push(monthData ? monthData.total : 0);
}

const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Collection Amount (₹)',
            data: monthlyAmounts,
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₹' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endsection
