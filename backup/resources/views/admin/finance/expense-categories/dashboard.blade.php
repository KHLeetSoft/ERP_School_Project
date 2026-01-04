@extends('admin.layout.app')

@section('content')
<div class="container-fluid">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h6 class="mb-0">Expense Categories Dashboard</h6>
		<a href="{{ route('admin.finance.expense-categories.index') }}" class="btn btn-sm btn-secondary">
			<i class="bx bx-left-arrow-alt"></i> Back
		</a>
	</div>

	<div class="row g-3">
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Total Categories</div>
					<div class="fs-5 fw-semibold">{{ $totalCategories ?? 0 }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Total Budget</div>
					<div class="fs-5 fw-semibold">₹{{ number_format($totalBudget ?? 0, 2) }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Total Expenses</div>
					<div class="fs-5 fw-semibold">₹{{ number_format($totalExpenses ?? 0, 2) }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Over Budget</div>
					<div class="fs-5 fw-semibold">{{ $overBudgetCategories ?? 0 }}</div>
				</div>
			</div>
		</div>
	</div>

	@php
		$__budgetedCount = isset($budgetData)
			? (is_object($budgetData) && method_exists($budgetData, 'count')
				? $budgetData->count()
				: (is_array($budgetData ?? null) ? count($budgetData) : 0))
			: 0;
		$__avgUtil = 0;
		if (isset($budgetData)) {
			if (is_object($budgetData) && method_exists($budgetData, 'avg')) {
				$__avgUtil = round($budgetData->avg('utilization'), 1);
			} elseif (is_array($budgetData) && count($budgetData)) {
				$__avgUtil = round(collect($budgetData)->avg('utilization'), 1);
			}
		}
	@endphp

	<div class="row g-3 mt-1">
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Budgeted Categories</div>
					<div class="fs-5 fw-semibold">{{ $__budgetedCount }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Avg Utilization</div>
					<div class="fs-5 fw-semibold">{{ $__avgUtil }}%</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Under Budget Categories</div>
					<div class="fs-5 fw-semibold">{{ isset($budgetComparison) ? (is_object($budgetComparison) && method_exists($budgetComparison,'filter') ? $budgetComparison->filter(fn($c)=>($c['variance'] ?? 0) >= 0)->count() : (is_array($budgetComparison ?? null) ? collect($budgetComparison)->filter(fn($c)=>($c['variance'] ?? 0) >= 0)->count() : 0)) : 0 }}</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="card shadow-sm border-0">
				<div class="card-body">
					<div class="text-muted small">Budget Status</div>
					<div class="fs-5 fw-semibold">Overview</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Monthly Trend (Top Categories)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecMonthlyTrend"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Category Distribution</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecCategoryDistribution"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Budget Utilization</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecBudgetUtilization"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Budget vs Actual (Monthly)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecBudgetVsActual"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Top 5 Categories by Spend</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecTopCategories"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Budget Status (Under vs Over)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecBudgetStatus"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Budget Variance (Budget - Actual)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecBudgetVariance"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Avg Spend per Transaction</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecAvgPerTxn"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-12">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Monthly Total Expenses</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 320px;">
						<canvas id="ecMonthlyTotal"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Monthly Trend (Stacked)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecMonthlyStacked"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Utilization Radar (Top 5)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecRadarUtilization"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Budget Share (Polar Area)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecPolarBudget"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Budget vs Actual (Bubble)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecBubbleBudgetActual"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row g-3 mt-1">
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Monthly Total (Mixed)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecMonthlyMixed"></canvas>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card shadow-sm border-0 h-100">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h6 class="mb-0">Utilization vs Monthly Spend (Scatter)</h6>
				</div>
				<div class="card-body">
					<div style="position: relative; height: 300px;">
						<canvas id="ecScatterUtilVsMonthly"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card shadow-sm border-0 mt-3">
		<div class="card-body">
			<div class="text-muted small mb-2">Top Categories</div>
			<div class="table-responsive">
				<table class="table table-sm align-middle mb-0">
					<thead>
						<tr>
							<th>Category</th>
							<th>Total</th>
							<th>Monthly</th>
							<th>Budget</th>
							<th>Utilization</th>
						</tr>
					</thead>
					<tbody>
						@forelse(($categoryPerformance ?? collect())->take(10) as $c)
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="rounded-circle me-2" style="width:14px;height:14px;background-color: {{ $c['color'] ?? '#999' }}"></div>
									{{ $c['name'] ?? '-' }}
								</div>
							</td>
							<td>₹{{ number_format($c['total_expenses'] ?? 0, 2) }}</td>
							<td>₹{{ number_format($c['monthly_expenses'] ?? 0, 2) }}</td>
							<td>{{ !empty($c['budget_limit']) ? '₹'.number_format($c['budget_limit'], 2) : '-' }}</td>
							<td>{{ isset($c['utilization']) ? ($c['utilization']).'%' : '-' }}</td>
						</tr>
						@empty
						<tr><td colspan="5" class="text-muted">No data available.</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
(function(){
	function initCharts(){
		const palette = ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#14b8a6','#f97316','#22c55e','#0ea5e9','#e11d48'];

		// Data from controller
		const monthLabels = @json($monthLabels ?? []);
		const monthlyTrend = @json($monthlyTrend ?? []);
		const categoryPerformance = @json(($categoryPerformance ?? collect())->values());
		const budgetData = @json($budgetData ?? []);
		const categoryDistribution = @json($categoryDistribution ?? []);
		const budgetComparison = @json($budgetComparison ?? []);

		// Helper maps
		const nameToColor = {};
		(categoryPerformance || []).forEach((c, idx) => { nameToColor[c.name] = c.color || palette[idx % palette.length]; });

		// Derived: monthly totals (sum of all categories per month)
		const monthlyTotals = (monthLabels || []).map((_, i) => {
			return Object.keys(monthlyTrend || {}).reduce((sum, key) => sum + Number(((monthlyTrend[key] || [])[i]) || 0), 0);
		});

		// Monthly Trend (multi-line)
		const trendEl = document.getElementById('ecMonthlyTrend');
		if (trendEl) {
			const datasets = Object.keys(monthlyTrend || {}).map((name, idx) => ({
				label: name,
				data: monthlyTrend[name] || [],
				borderColor: nameToColor[name] || palette[idx % palette.length],
				backgroundColor: 'rgba(0,0,0,0)',
				tension: 0.3,
				pointRadius: 2
			}));
			new Chart(trendEl, {
				type: 'line',
				data: { labels: monthLabels, datasets },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
			});
		}

		// Top 5 Categories (horizontal bar)
		const topEl = document.getElementById('ecTopCategories');
		if (topEl) {
			const top = (categoryPerformance || []).slice(0,5);
			const labels = top.map(t => t.name);
			const values = top.map(t => Number(t.total_expenses || 0));
			const colors = top.map((t, idx) => t.color || palette[idx % palette.length]);
			new Chart(topEl, {
				type: 'bar',
				data: { labels, datasets: [{ label: 'Total', data: values, backgroundColor: colors }] },
				options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
			});
		}

		// Budget Status (doughnut)
		const statusEl = document.getElementById('ecBudgetStatus');
		if (statusEl) {
			const over = (budgetData || []).filter(d => Number(d.utilization || 0) > 100).length;
			const under = (budgetData || []).filter(d => Number(d.utilization || 0) <= 100).length;
			new Chart(statusEl, {
				type: 'doughnut',
				data: { labels: ['Under Budget','Over Budget'], datasets: [{ data: [under, over], backgroundColor: ['#10b981','#ef4444'] }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
			});
		}

		// Budget Variance (diverging bar)
		const varEl = document.getElementById('ecBudgetVariance');
		if (varEl) {
			const labels = (budgetComparison || []).map(d => d.name);
			const variances = (budgetComparison || []).map(d => Number(d.variance || 0));
			const colors = variances.map(v => v >= 0 ? 'rgba(34,197,94,0.8)' : 'rgba(239,68,68,0.8)');
			new Chart(varEl, {
				type: 'bar',
				data: { labels, datasets: [{ label: 'Variance', data: variances, backgroundColor: colors }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
			});
		}

		// Avg spend per transaction (top 10, horizontal)
		const avgEl = document.getElementById('ecAvgPerTxn');
		if (avgEl) {
			const entries = (categoryPerformance || []).map(c => ({ name: c.name, avg: (Number(c.expense_count || 0) > 0 ? Number(c.total_expenses || 0) / Number(c.expense_count || 0) : 0), color: c.color }));
			entries.sort((a,b) => b.avg - a.avg);
			const top = entries.slice(0, 10);
			new Chart(avgEl, {
				type: 'bar',
				data: { labels: top.map(e => e.name), datasets: [{ label: 'Avg Amount', data: top.map(e => e.avg), backgroundColor: top.map((e,idx)=> e.color || palette[idx % palette.length]) }] },
				options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
			});
		}

		// Monthly total expenses
		const mtEl = document.getElementById('ecMonthlyTotal');
		if (mtEl) {
			new Chart(mtEl, {
				type: 'line',
				data: { labels: monthLabels, datasets: [{ label: 'Total', data: monthlyTotals, borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.2)', tension: 0.3, pointRadius: 2 }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
			});
		}

		// Category Distribution (pie)
		const distEl = document.getElementById('ecCategoryDistribution');
		if (distEl) {
			const labels = (categoryDistribution || []).map(d => d.name);
			const values = (categoryDistribution || []).map(d => Number(d.amount || 0));
			const colors = (categoryDistribution || []).map((d, idx) => d.color || palette[idx % palette.length]);
			new Chart(distEl, {
				type: 'pie',
				data: { labels, datasets: [{ data: values, backgroundColor: colors }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
			});
		}

		// Budget Utilization (bar %)
		const utilEl = document.getElementById('ecBudgetUtilization');
		if (utilEl) {
			const labels = (budgetData || []).map(d => d.name);
			const values = (budgetData || []).map(d => Number(d.utilization || 0));
			const colors = (budgetData || []).map((d, idx) => d.color || palette[idx % palette.length]);
			new Chart(utilEl, {
				type: 'bar',
				data: { labels, datasets: [{ label: 'Utilization %', data: values, backgroundColor: colors }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 120, ticks: { callback: (v)=> v+"%" } } } }
			});
		}

		// Budget vs Actual (bar)
		const baEl = document.getElementById('ecBudgetVsActual');
		if (baEl) {
			const labels = (budgetComparison || []).map(d => d.name);
			const budget = (budgetComparison || []).map(d => Number(d.budget || 0));
			const actual = (budgetComparison || []).map(d => Number(d.actual || 0));
			new Chart(baEl, {
				type: 'bar',
				data: { 
					labels, 
					datasets: [
						{ label: 'Budget', data: budget, backgroundColor: 'rgba(59,130,246,0.6)' },
						{ label: 'Actual', data: actual, backgroundColor: 'rgba(16,185,129,0.7)' }
					]
				},
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
			});
		}

		// Monthly Trend (stacked bar)
		const stackedEl = document.getElementById('ecMonthlyStacked');
		if (stackedEl) {
			const datasets = Object.keys(monthlyTrend || {}).map((name, idx) => ({
				label: name,
				data: monthlyTrend[name] || [],
				backgroundColor: nameToColor[name] || palette[idx % palette.length]
			}));
			new Chart(stackedEl, {
				type: 'bar',
				data: { labels: monthLabels, datasets },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { x:{ stacked:true }, y:{ stacked:true, beginAtZero:true } } }
			});
		}

		// Utilization Radar (Top 5)
		const radarEl = document.getElementById('ecRadarUtilization');
		if (radarEl) {
			const top = (categoryPerformance || []).slice(0,5);
			new Chart(radarEl, {
				type: 'radar',
				data: { labels: top.map(t=>t.name), datasets: [{ label: 'Utilization %', data: top.map(t=> Number(t.utilization || 0)), backgroundColor: 'rgba(59,130,246,0.2)', borderColor: '#3b82f6', pointBackgroundColor: '#3b82f6' }] },
				options: { responsive: true, maintainAspectRatio: false, scales: { r: { suggestedMin: 0, suggestedMax: 120, ticks: { callback: (v)=> v+'%' } } } }
			});
		}

		// Budget Share (Polar Area)
		const polarEl = document.getElementById('ecPolarBudget');
		if (polarEl) {
			const entries = (categoryPerformance || []).filter(c=> Number(c.budget_limit || 0) > 0).slice(0,10);
			new Chart(polarEl, {
				type: 'polarArea',
				data: { labels: entries.map(e=>e.name), datasets: [{ data: entries.map(e=> Number(e.budget_limit || 0)), backgroundColor: entries.map((e,idx)=> e.color || palette[idx % palette.length]) }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
			});
		}

		// Budget vs Actual (Bubble)
		const bubbleEl = document.getElementById('ecBubbleBudgetActual');
		if (bubbleEl) {
			const items = (budgetComparison || []).slice(0,20);
			const datasets = items.map((it, idx) => ({
				label: it.name,
				data: [{ x: Number(it.budget || 0), y: Number(it.actual || 0), r: Math.max(4, Math.min(18, Math.abs(Number(it.variance || 0)) / 1000)) }],
				backgroundColor: 'rgba(99,102,241,0.5)',
				borderColor: '#6366f1'
			}));
			new Chart(bubbleEl, {
				type: 'bubble',
				data: { datasets },
				options: { responsive: true, maintainAspectRatio: false, scales: { x: { title: { display: true, text: 'Budget' } }, y: { beginAtZero: true, title: { display: true, text: 'Actual' } } }, plugins: { legend: { display: false } } }
			});
		}

		// Monthly Total (Mixed: bar + line)
		const mixedEl = document.getElementById('ecMonthlyMixed');
		if (mixedEl) {
			new Chart(mixedEl, {
				type: 'bar',
				data: { labels: monthLabels, datasets: [
					{ type: 'bar', label: 'Total (Bar)', data: monthlyTotals, backgroundColor: 'rgba(59,130,246,0.6)' },
					{ type: 'line', label: 'Total (Line)', data: monthlyTotals, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.2)', tension: 0.3, pointRadius: 2 }
				]},
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } }
			});
		}

		// Utilization vs Monthly Spend (Scatter)
		const scatterEl = document.getElementById('ecScatterUtilVsMonthly');
		if (scatterEl) {
			const points = (categoryPerformance || []).map(c => ({ x: Number(c.utilization || 0), y: Number(c.monthly_expenses || 0) }));
			new Chart(scatterEl, {
				type: 'scatter',
				data: { datasets: [{ label: 'Categories', data: points, backgroundColor: 'rgba(234,88,12,0.7)' }] },
				options: { responsive: true, maintainAspectRatio: false, scales: { x: { title: { display: true, text: 'Utilization %' }, min: 0, max: 150 }, y: { beginAtZero: true, title: { display: true, text: 'Monthly Spend' } } } }
			});
		}
	}

	if (window.Chart) { initCharts(); }
	else {
		var s = document.createElement('script');
		s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js';
		s.onload = initCharts;
		document.head.appendChild(s);
	}
})();
</script>
@endsection


