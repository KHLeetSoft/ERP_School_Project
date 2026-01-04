@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">RFID Attendance Dashboard</h6>
        <a href="{{ route('admin.attendance.rfid.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Entries (In/Out) - Last 7 Days</h6></div>
                <div class="card-body"><canvas id="inOutChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h6 class="mb-0">Top Devices</h6></div>
                <div class="card-body"><canvas id="deviceBar"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header"><h6 class="mb-0">Direction Split (Today)</h6></div>
                <div class="card-body"><canvas id="todayDonut"></canvas></div>
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
        const inSeries = {!! json_encode($inSeries) !!};
        const outSeries = {!! json_encode($outSeries) !!};
        const inOutCtx = document.getElementById('inOutChart');
        if(inOutCtx){
            new Chart(inOutCtx, {
                type: 'line',
                data: { labels, datasets: [
                    { label: 'In', data: inSeries, borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.2)' },
                    { label: 'Out', data: outSeries, borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.2)' },
                ]},
                options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero: true } } }
            });
        }

        const deviceCtx = document.getElementById('deviceBar');
        if(deviceCtx){
            const dl = {!! json_encode($topDevices->pluck('device_name')->map(fn($d)=> $d ?? 'Unknown')) !!};
            const dv = {!! json_encode($topDevices->pluck('cnt')) !!};
            new Chart(deviceCtx, {
                type: 'bar',
                data: { labels: dl, datasets: [{ label: 'Scans', data: dv, backgroundColor: '#3b82f6' }] },
                options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero:true } } }
            });
        }

        const donutCtx = document.getElementById('todayDonut');
        if(donutCtx){
            const todayIn = inSeries[inSeries.length-1] || 0;
            const todayOut = outSeries[outSeries.length-1] || 0;
            new Chart(donutCtx, {
                type: 'doughnut',
                data: { labels: ['In','Out'], datasets: [{ data: [todayIn, todayOut], backgroundColor: ['#22c55e','#ef4444'] }] },
                options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } } }
            });
        }
    })();
</script>
@endsection


