@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Invoices Dashboard</h6>
        <a href="{{ route('admin.finance.invoice.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
    </div>
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Monthly Revenue (6 months)</h6></div>
                <div class="card-body"><canvas id="revenueLine"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Invoices by Status</h6></div>
                <div class="card-body"><canvas id="statusPie"></canvas></div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Average Invoice Value</h6></div>
                <div class="card-body"><canvas id="avgLine"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Monthly Status Counts (Stacked)</h6></div>
                <div class="card-body"><canvas id="statusStacked"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Top Customers by Revenue</h6></div>
                <div class="card-body"><canvas id="topCustomers"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Paid vs Unpaid (Monthly)</h6></div>
                <div class="card-body"><canvas id="paidUnpaid"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Cumulative Revenue</h6></div>
                <div class="card-body"><canvas id="cumulative"></canvas></div>
            </div>
        </div>
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header"><h6 class="mb-0">Tax vs Discount (Monthly)</h6></div>
                <div class="card-body"><canvas id="taxDiscount"></canvas></div>
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
        const statusLabels = {!! json_encode($statusCounts->keys()) !!};
        const statusValues = {!! json_encode($statusCounts->values()) !!};
        const statusSeries = {!! json_encode($statusSeries) !!};
        const avgSeries = {!! json_encode($avgSeries) !!};
        const topL = {!! json_encode($topCustomerLabels) !!};
        const topV = {!! json_encode($topCustomerValues) !!};
        const rev = document.getElementById('revenueLine');
        if (rev) {
            new Chart(rev, { type: 'line', data: { labels, datasets: [{ label: 'Revenue', data: totals, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
        }
        const pie = document.getElementById('statusPie');
        if (pie) {
            new Chart(pie, { type: 'pie', data: { labels: statusLabels, datasets: [{ data: statusValues, backgroundColor: ['#22c55e','#3b82f6','#f59e0b','#ef4444','#6b7280'] }] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } } } });
        }
        const avg = document.getElementById('avgLine');
        if (avg) {
            new Chart(avg, { type: 'line', data: { labels, datasets: [{ label: 'Average', data: avgSeries, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
        }
        const stacked = document.getElementById('statusStacked');
        if (stacked) {
            const colors = { draft:'#6b7280', sent:'#3b82f6', paid:'#22c55e', overdue:'#f59e0b', cancelled:'#ef4444' };
            const datasets = Object.keys(statusSeries).map(k => ({ label: k.charAt(0).toUpperCase()+k.slice(1), data: statusSeries[k], backgroundColor: colors[k] || '#94a3b8' }));
            new Chart(stacked, { type: 'bar', data: { labels, datasets }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { x: { stacked:true }, y: { stacked:true, beginAtZero:true } } } });
        }
        const top = document.getElementById('topCustomers');
        if (top) {
            new Chart(top, { type: 'bar', data: { labels: topL, datasets: [{ label: 'Revenue', data: topV, backgroundColor: '#9333ea' }] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { display:false } }, scales: { y: { beginAtZero:true } } } });
        }
        const pu = document.getElementById('paidUnpaid');
        if (pu) {
            const paid = {!! json_encode($paidSeries) !!};
            const unpaid = {!! json_encode($unpaidSeries) !!};
            new Chart(pu, { type: 'bar', data: { labels, datasets: [
                { label: 'Paid', data: paid, backgroundColor: '#22c55e' },
                { label: 'Unpaid', data: unpaid, backgroundColor: '#ef4444' },
            ] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero:true } } } });
        }
        const cum = document.getElementById('cumulative');
        if (cum) {
            const series = {!! json_encode($cumulativeSeries) !!};
            new Chart(cum, { type: 'line', data: { labels, datasets: [{ label: 'Cumulative', data: series, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)' }] }, options: { responsive:true, maintainAspectRatio:false, scales: { y: { beginAtZero:true } } } });
        }
        const td = document.getElementById('taxDiscount');
        if (td) {
            const tax = {!! json_encode($taxSeries) !!};
            const disc = {!! json_encode($discountSeries) !!};
            new Chart(td, { type: 'bar', data: { labels, datasets: [
                { label: 'Tax', data: tax, backgroundColor: '#3b82f6' },
                { label: 'Discount', data: disc, backgroundColor: '#f59e0b' },
            ] }, options: { responsive:true, maintainAspectRatio:false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero:true } } } });
        }
    })();
</script>
@endsection


