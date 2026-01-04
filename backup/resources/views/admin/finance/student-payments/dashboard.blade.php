@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Student Payments Dashboard</h6>
        <a href="{{ route('admin.finance.student-payments.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total This Month</div>
                    <div class="fs-5 fw-semibold">{{ number_format($totalThisMonth, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Total Last Month</div>
                    <div class="fs-5 fw-semibold">{{ number_format($totalLastMonth, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Growth vs Last Month</div>
                    <div class="fs-5 fw-semibold">{{ is_null($growthPct) ? '-' : ($growthPct.'%') }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Avg Payment (This Month)</div>
                    <div class="fs-5 fw-semibold">{{ number_format($avgThisMonth, 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Refunded (This Month)</div>
                    <div class="fs-5 fw-semibold">{{ number_format($refundedAmountThisMonth, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Pending Count (This Month)</div>
                    <div class="fs-5 fw-semibold">{{ $pendingCountThisMonth }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-muted small">Method Totals</div>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach($methodAmountTotals as $method => $sum)
                            <div><span class="badge bg-light text-dark">{{ ucfirst($method) }}</span> {{ number_format($sum, 2) }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Monthly Amount Received (6 months)</h6></div>
                <div class="card-body"><canvas id="paymentsLine"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Monthly Amount Received (12 months)</h6></div>
                <div class="card-body"><canvas id="paymentsLine12"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Daily Trend (Last 30 days)</h6></div>
                <div class="card-body"><canvas id="last30"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Payments by Status (Monthly Stacked)</h6></div>
                <div class="card-body"><canvas id="statusStacked"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Payment Methods Distribution</h6></div>
                <div class="card-body"><canvas id="methodPie"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Average Payment Value</h6></div>
                <div class="card-body"><canvas id="avgLine"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Unique Payers per Month</h6></div>
                <div class="card-body"><canvas id="uniquePayers"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Top Students by Amount</h6></div>
                <div class="card-body"><canvas id="topStudents"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Top Classes by Amount</h6></div>
                <div class="card-body"><canvas id="topClasses"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Completed vs Others (Monthly)</h6></div>
                <div class="card-body"><canvas id="completedOthers"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Cumulative Amount Received</h6></div>
                <div class="card-body"><canvas id="cumulative"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Method Amounts per Month (Stacked)</h6></div>
                <div class="card-body"><canvas id="methodStacked"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Weekday Distribution</h6></div>
                <div class="card-body"><canvas id="weekday"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Method Share % (Monthly 100% Stacked)</h6></div>
                <div class="card-body"><canvas id="methodShare"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Amount Histogram (All Time)</h6></div>
                <div class="card-body"><canvas id="amountHist"></canvas></div>
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
    const totals = {!! json_encode($totals) !!};
    const totals12 = {!! json_encode($totals12) !!};
    const labels12 = {!! json_encode($labels12) !!};
    const statusSeries = {!! json_encode($statusSeries) !!};
    const methodCounts = {!! json_encode($methodCounts) !!};
    const avgSeries = {!! json_encode($avgSeries) !!};
    const topLabels = {!! json_encode($topLabels) !!};
    const topValues = {!! json_encode($topValues) !!};
    const completed = {!! json_encode($completedSeries) !!};
    const others = {!! json_encode($othersSeries) !!};
    const statusCounts = {!! json_encode($statusCounts) !!};
    const last30Labels = {!! json_encode($last30Labels) !!};
    const last30Series = {!! json_encode($last30Series) !!};
    const ma7Series = {!! json_encode($ma7Series) !!};
    const methodMonthlySeries = {!! json_encode($methodMonthlySeries) !!};
    const methodShareSeries = {!! json_encode($methodShareSeries) !!};
    const uniquePayers = {!! json_encode($uniquePayers) !!};
    const weekdayLabels = {!! json_encode($weekdayLabels) !!};
    const weekdayValues = {!! json_encode($weekdayValues) !!};
    const topClassLabels = {!! json_encode($topClassLabels) !!};
    const topClassValues = {!! json_encode($topClassValues) !!};
    const colors = {
        pending:'#f59e0b', completed:'#22c55e', failed:'#ef4444', refunded:'#6b7280',
        cash:'#16a34a', card:'#0ea5e9', bank:'#9333ea', online:'#f97316'
    };

    const line = document.getElementById('paymentsLine');
    if (line) {
        new Chart(line, { type: 'line', data: { labels, datasets: [{ label: 'Amount', data: totals, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
    }
    const line12 = document.getElementById('paymentsLine12');
    if (line12) {
        new Chart(line12, { type: 'line', data: { labels: labels12, datasets: [{ label: 'Amount', data: totals12, borderColor: '#1f2937', backgroundColor: 'rgba(31,41,55,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
    }
    const stacked = document.getElementById('statusStacked');
    if (stacked) {
        const datasets = Object.keys(statusSeries).map(k => ({ label: k.charAt(0).toUpperCase()+k.slice(1), data: statusSeries[k], backgroundColor: colors[k] || '#94a3b8' }));
        new Chart(stacked, { type: 'bar', data: { labels, datasets }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { x:{ stacked:true }, y:{ stacked:true, beginAtZero:true } } } });
    }
    const last30 = document.getElementById('last30');
    if (last30) {
        new Chart(last30, { type: 'bar', data: { labels: last30Labels, datasets: [
            { label: 'Amount', data: last30Series, backgroundColor: 'rgba(59,130,246,0.6)' },
            { label: '7-day MA', data: ma7Series, type: 'line', borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.2)' },
        ] }, options: { responsive:true, maintainAspectRatio:false, scales: { y:{ beginAtZero:true } } } });
    }
    const pie = document.getElementById('methodPie');
    if (pie) {
        const mLabels = Object.keys(methodCounts);
        const mValues = Object.values(methodCounts);
        const mColors = mLabels.map(k => colors[k] || '#cbd5e1');
        new Chart(pie, { type: 'pie', data: { labels: mLabels, datasets: [{ data: mValues, backgroundColor: mColors }] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } } } });
    }
    const avg = document.getElementById('avgLine');
    if (avg) {
        new Chart(avg, { type: 'line', data: { labels, datasets: [{ label: 'Average', data: avgSeries, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y:{ beginAtZero:true } } } });
    }
    const top = document.getElementById('topStudents');
    if (top) {
        new Chart(top, { type: 'bar', data: { labels: topLabels, datasets: [{ label: 'Amount', data: topValues, backgroundColor: '#9333ea' }] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { display:false } }, scales: { y: { beginAtZero:true } } } });
    }
    const topC = document.getElementById('topClasses');
    if (topC) {
        new Chart(topC, { type: 'bar', data: { labels: topClassLabels, datasets: [{ label: 'Amount', data: topClassValues, backgroundColor: '#f59e0b' }] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { display:false } }, scales: { y: { beginAtZero:true } } } });
    }
    const co = document.getElementById('completedOthers');
    if (co) {
        new Chart(co, { type: 'bar', data: { labels, datasets: [
            { label: 'Completed', data: completed, backgroundColor: colors.completed },
            { label: 'Others', data: others, backgroundColor: '#ef4444' },
        ] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero:true } } } });
    }
    // Success rate and status amounts per month
    const successRate = {!! json_encode($successRateSeries) !!};
    const pendingAmountSeries = {!! json_encode($pendingAmountSeries) !!};
    const failedAmountSeries = {!! json_encode($failedAmountSeries) !!};
    const refundedAmountSeries = {!! json_encode($refundedAmountSeries) !!};
    const successCanvas = document.createElement('canvas');
    successCanvas.id = 'successRate';
    document.querySelector('.container-fluid .row.g-3').appendChild((() => { const d = document.createElement('div'); d.className = 'col-12'; const c = document.createElement('div'); c.className = 'card shadow-sm border-0'; const h = document.createElement('div'); h.className = 'card-header'; h.innerHTML = '<h6 class="mb-0">Success Rate & Status Amounts</h6>'; const b = document.createElement('div'); b.className = 'card-body'; b.appendChild(successCanvas); c.appendChild(h); c.appendChild(b); d.appendChild(c); return d; })());
    new Chart(successCanvas, { type: 'bar', data: { labels, datasets: [
        { type: 'line', label: 'Success %', data: successRate, borderColor: '#16a34a', yAxisID: 'y1' },
        { label: 'Pending Amt', data: pendingAmountSeries, backgroundColor: '#f59e0b', yAxisID: 'y' },
        { label: 'Failed Amt', data: failedAmountSeries, backgroundColor: '#ef4444', yAxisID: 'y' },
        { label: 'Refunded Amt', data: refundedAmountSeries, backgroundColor: '#6b7280', yAxisID: 'y' },
    ] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero:true }, y1: { beginAtZero:true, position: 'right', min:0, max:100 } } } });

    // New vs Returning payers per month
    const newReturningCanvas = document.createElement('canvas');
    newReturningCanvas.id = 'newReturning';
    document.querySelector('.container-fluid .row.g-3').appendChild((() => { const d = document.createElement('div'); d.className = 'col-12'; const c = document.createElement('div'); c.className = 'card shadow-sm border-0'; const h = document.createElement('div'); h.className = 'card-header'; h.innerHTML = '<h6 class="mb-0">New vs Returning Payers</h6>'; const b = document.createElement('div'); b.className = 'card-body'; b.appendChild(newReturningCanvas); c.appendChild(h); c.appendChild(b); d.appendChild(c); return d; })());
    new Chart(newReturningCanvas, { type: 'bar', data: { labels, datasets: [
        { label: 'New', data: {!! json_encode($newPayersSeries) !!}, backgroundColor: '#3b82f6' },
        { label: 'Returning', data: {!! json_encode($returningPayersSeries) !!}, backgroundColor: '#10b981' },
    ] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero:true, ticks: { stepSize: 1 } } } } });
    const cum = document.getElementById('cumulative');
    if (cum) {
        new Chart(cum, { type: 'line', data: { labels, datasets: [{ label: 'Cumulative', data: {!! json_encode($cumulativeSeries) !!}, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
    }
    const methodStacked = document.getElementById('methodStacked');
    if (methodStacked) {
        const mDatasets = Object.keys(methodMonthlySeries).map(k => ({ label: k.charAt(0).toUpperCase()+k.slice(1), data: methodMonthlySeries[k], backgroundColor: colors[k] || '#94a3b8' }));
        new Chart(methodStacked, { type: 'bar', data: { labels, datasets: mDatasets }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { x:{ stacked:true }, y:{ stacked:true, beginAtZero:true } } } });
    }
    const methodShare = document.getElementById('methodShare');
    if (methodShare) {
        const sDatasets = Object.keys(methodShareSeries).map(k => ({ label: k.charAt(0).toUpperCase()+k.slice(1), data: methodShareSeries[k], backgroundColor: colors[k] || '#94a3b8' }));
        new Chart(methodShare, { type: 'bar', data: { labels, datasets: sDatasets }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { x:{ stacked:true }, y:{ stacked:true, beginAtZero:true, max:100 } } } });
    }
    const amountHist = document.getElementById('amountHist');
    if (amountHist) {
        new Chart(amountHist, { type: 'bar', data: { labels: {!! json_encode($histLabels) !!}, datasets: [{ label: 'Count', data: {!! json_encode($histValues) !!}, backgroundColor: '#0ea5e9' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y:{ beginAtZero:true, ticks: { stepSize: 1 } } } } });
    }
    const uniqueP = document.getElementById('uniquePayers');
    if (uniqueP) {
        new Chart(uniqueP, { type: 'line', data: { labels, datasets: [{ label: 'Unique Payers', data: uniquePayers, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y:{ beginAtZero:true, ticks: { stepSize: 1 } } } } });
    }
    const week = document.getElementById('weekday');
    if (week) {
        new Chart(week, { type: 'bar', data: { labels: weekdayLabels, datasets: [{ label: 'Amount', data: weekdayValues, backgroundColor: '#64748b' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
    }
})();
</script>
@endsection


