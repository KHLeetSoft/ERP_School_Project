@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h6 class="mb-0">Finance Reports Dashboard</h6>
		<div class="d-flex gap-2">
			<a href="{{ route('admin.finance.reports.index') }}" class="btn btn-sm btn-secondary"><i class="bx bx-left-arrow-alt"></i> Back</a>
		</div>
	</div>

	<div class="row g-3">
		<div class="col-md-4">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Months Covered</div>
					<div class="fs-5 fw-semibold">{{ count($labels) }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Total Income (Mock)</div>
					<div class="fs-5 fw-semibold">₹{{ number_format(array_sum($incomeSeries), 2) }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Total Expenses (Mock)</div>
					<div class="fs-5 fw-semibold">₹{{ number_format(array_sum($expenseSeries), 2) }}</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-12">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Income vs Expenses (Mock)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 340px;">
						<canvas id="frIncomeVsExpenses"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Stacked Area: Income vs Expenses</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frStackedArea"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Net Amount per Month</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frNetBar"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Cumulative Net</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frCumulativeNet"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Income Share</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frShareDonut"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">7-period Moving Average</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frMovingAverage"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Income/Expense Ratio</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frRatio"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Pareto: Income by Month</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frParetoIncome"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">MoM Change (Income & Expense)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frMoMChange"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">100% Stacked Share</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frPercentStacked"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Income vs Expense (Correlation)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="frScatterCorr"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-12">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Cumulative Income vs Expenses</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 320px;">
						<canvas id="frCumLines"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
(function(){
	function init(){
		const labels = @json($labels ?? []);
		const income = @json($incomeSeries ?? []);
		const expenses = @json($expenseSeries ?? []);
		const net = labels.map((_,i)=> (income[i]||0)-(expenses[i]||0));
		const cumNet = net.reduce((acc, n, i) => { acc.push((acc[i-1]||0)+n); return acc; }, []);
		function movingAvg(arr, k){
			const out=[];
			for(let i=0;i<arr.length;i++){
				const s=Math.max(0,i-k+1);
				const win=arr.slice(s,i+1);
				out.push(win.reduce((a,b)=>a+b,0)/win.length);
			}
			return out;
		}
		const incomeMA = movingAvg(income, Math.min(3, income.length));
		const expenseMA = movingAvg(expenses, Math.min(3, expenses.length));
		const ratio = labels.map((_,i)=> { const e=expenses[i]||0; return e>0 ? (income[i]||0)/e : 0; });
		const cumIncome = income.reduce((acc, v, i)=>{ acc.push((acc[i-1]||0)+v); return acc; }, []);
		const cumExpense = expenses.reduce((acc, v, i)=>{ acc.push((acc[i-1]||0)+v); return acc; }, []);
		const pctIncome = labels.map((_,i)=> { const tot=(income[i]||0)+(expenses[i]||0); return tot>0 ? (income[i]||0)/tot*100 : 0; });
		const pctExpense = labels.map((_,i)=> 100 - pctIncome[i]);
		const momIncome = labels.map((_,i)=> i===0?0: ((income[i]||0)-(income[i-1]||0)) );
		const momExpense = labels.map((_,i)=> i===0?0: ((expenses[i]||0)-(expenses[i-1]||0)) );
		
		// Simple linear regression for income vs expense
		function linReg(xs, ys){
			const n=xs.length;
			const sx=xs.reduce((a,b)=>a+b,0);
			const sy=ys.reduce((a,b)=>a+b,0);
			const sxx=xs.reduce((a,b)=>a+b*b,0);
			const sxy=xs.reduce((a,b,i)=>a+b*ys[i],0);
			const m=(n*sxy - sx*sy)/(n*sxx - sx*sx || 1);
			const c=(sy - m*sx)/n;
			return {m,c};
		}
		const {m:regM, c:regC} = linReg(expenses, income);
		const maxExpense = Math.max.apply(null, (expenses || []).concat(0));

		// Income vs Expenses
		const el = document.getElementById('frIncomeVsExpenses');
		if (el) {
			new Chart(el, {
				type: 'bar',
				data: { labels, datasets: [
					{ type: 'bar', label: 'Income', data: income, backgroundColor: 'rgba(34,197,94,0.7)' },
					{ type: 'bar', label: 'Expenses', data: expenses, backgroundColor: 'rgba(239,68,68,0.7)' },
					{ type: 'line', label: 'Net', data: net, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)', tension: 0.3, pointRadius: 2 }
				] },
				options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } }
			});
		}

		// Stacked Area
		const sa = document.getElementById('frStackedArea');
		if (sa) {
			new Chart(sa, { type: 'line', data: { labels, datasets: [
				{ label:'Income', data: income, borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,0.25)', fill:true, tension:0.3 },
				{ label:'Expenses', data: expenses, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.25)', fill:true, tension:0.3 }
			]}, options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales: { y:{ beginAtZero:true, stacked:true }, x:{ stacked:true } } } });
		}

		// Net Bar
		const nb = document.getElementById('frNetBar');
		if (nb) {
			new Chart(nb, {
				type:'bar',
				data:{ labels, datasets:[
					{ label:'Net', data: net, backgroundColor: net.map(v=> v>=0? 'rgba(34,197,94,0.7)':'rgba(239,68,68,0.7)') }
				] },
				options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
			});
		}

		// Cumulative Net
		const cn = document.getElementById('frCumulativeNet');
		if (cn) {
			new Chart(cn, { type:'line', data:{ labels, datasets:[
				{ label:'Cumulative Net', data: cumNet, borderColor:'#6366f1', backgroundColor:'rgba(99,102,241,0.2)', tension:0.3, pointRadius:2 }
			] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } } });
		}

		// Share Donut
		const sd = document.getElementById('frShareDonut');
		if (sd) {
			const totI = income.reduce((a,b)=>a+b,0);
			const totE = expenses.reduce((a,b)=>a+b,0);
			new Chart(sd, { type:'doughnut', data:{ labels:['Income','Expenses'], datasets:[
				{ data:[totI, totE], backgroundColor:['#22c55e','#ef4444'] }
			] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } } } });
		}

		// Moving Average
		const ma = document.getElementById('frMovingAverage');
		if (ma) {
			new Chart(ma, { type:'line', data:{ labels, datasets:[
				{ label:'Income MA', data: incomeMA, borderColor:'#0ea5e9', backgroundColor:'rgba(14,165,233,0.2)', tension:0.3 },
				{ label:'Expense MA', data: expenseMA, borderColor:'#f59e0b', backgroundColor:'rgba(245,158,11,0.2)', tension:0.3 }
			] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } } });
		}

		// Ratio Line
		const rl = document.getElementById('frRatio');
		if (rl) {
			new Chart(rl, { type:'line', data:{ labels, datasets:[
				{ label:'Income / Expense', data: ratio, borderColor:'#111827', backgroundColor:'rgba(17,24,39,0.15)', tension:0.2, pointRadius:2 }
			] }, options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{ position:'bottom' } }, scales:{ y:{ beginAtZero:true } } } });
		}
// Pareto Income
const pr = document.getElementById('frParetoIncome');
if (pr) {
    const pairs = labels.map((l, i) => ({ l, v: income[i] || 0 }))
        .sort((a, b) => b.v - a.v);

    const sortedLabels = pairs.map(p => p.l);
    const sortedVals = pairs.map(p => p.v);

    const cum = sortedVals.reduce((acc, v, i) => {
        const s = (acc[i - 1] || 0) + v;
        acc.push(s);
        return acc;
    }, []);

    const total = cum[cum.length - 1] || 1; // avoid division by 0
    const cumPct = cum.map(v => (v / total) * 100);

    new Chart(pr, {
        type: 'bar',
        data: {
            labels: sortedLabels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Income',
                    data: sortedVals,
                    backgroundColor: 'rgba(34,197,94,0.7)',
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'Cumulative %',
                    data: cumPct,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,0.2)',
                    yAxisID: 'y1',
                    tension: 0.2,
                    pointRadius: 2,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Income' } },
                y1: { beginAtZero: true, max: 100, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Cumulative %' } }
            },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

	
		// MoM Change
		const mm = document.getElementById('frMoMChange');
		if (mm) {
			new Chart(mm, { type: 'bar', data: { labels, datasets: [
				{ label: 'Income Δ', data: momIncome, backgroundColor: 'rgba(34,197,94,0.7)' },
				{ label: 'Expense Δ', data: momExpense, backgroundColor: 'rgba(239,68,68,0.7)' }
			] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } } });
		}

		// 100% Stacked Share
		const ps = document.getElementById('frPercentStacked');
		if (ps) {
			new Chart(ps, { type: 'bar', data: { labels, datasets: [
				{ label: 'Income %', data: pctIncome, backgroundColor: 'rgba(34,197,94,0.7)' },
				{ label: 'Expense %', data: pctExpense, backgroundColor: 'rgba(239,68,68,0.7)' }
			] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true, max: 100, ticks: { callback: v=> v+'%' } } } } });
		}

		// Correlation Scatter
		const sc = document.getElementById('frScatterCorr');
		if (sc) {
			new Chart(sc, { type: 'scatter', data: { datasets: [
				{ label: 'Points', data: labels.map((_, i) => ({ x: expenses[i]||0, y: income[i]||0 })), backgroundColor: 'rgba(59,130,246,0.7)' },
				{ type: 'line', label: 'Regression', data: [ {x:0, y:regC}, {x:maxExpense, y: regM*maxExpense+regC } ], borderColor: '#111827', backgroundColor: 'rgba(17,24,39,0.1)', pointRadius: 0 }
			] }, options: { responsive: true, maintainAspectRatio: false, scales: { x: { title: { display: true, text: 'Expenses'} }, y: { title: { display: true, text: 'Income'} } }, plugins: { legend: { position: 'bottom' } } } });
		}

		// Cumulative Income vs Expense
		const cl = document.getElementById('frCumLines');
		if (cl) {
			new Chart(cl, { type: 'line', data: { labels, datasets: [
				{ label:'Cumulative Income', data: cumIncome, borderColor:'#22c55e', backgroundColor:'rgba(34,197,94,0.2)', tension:0.3, pointRadius:2 },
				{ label:'Cumulative Expenses', data: cumExpense, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.2)', tension:0.3, pointRadius:2 }
			] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } });
		}
	}

	if (window.Chart) {
		init();
	} else {
		const s = document.createElement('script');
		s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
		s.onload = init;
		document.head.appendChild(s);
	}
})();
</script>
@endsection
