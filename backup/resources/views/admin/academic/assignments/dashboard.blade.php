@extends('admin.layout.app')
@section('content')
<div class="container-fluid py-3">
    <h4 class="mb-3 fw-bold text-primary"><i class="bx bx-bar-chart-alt-2 me-2"></i>Assignments Dashboard</h4>
  <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-primary btn-lg rounded-pill">
            <i class="bx bx-list-ul me-1"></i> View All Assignments
        </a>
    {{-- Stat Cards --}}
    <div class="row g-3 mb-3">
        @foreach(['Total'=>$total, 'Pending'=>$pending, 'Completed'=>$completed, 'Delayed'=>$delayed] as $label=>$count)
        <div class="col-md-3 col-sm-6">
            <div class="card shadow-sm border-0 text-center p-2">
                <h6 class="fw-bold">{{ $label }}</h6>
                <p class="display-6 mb-0">{{ $count }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Charts --}}
    <div class="row g-3">
        <div class="col-md-6 col-sm-12"><canvas id="statusPie" height="180"></canvas></div>
        <div class="col-md-6 col-sm-12"><canvas id="monthlyBar" height="180"></canvas></div>
        <div class="col-md-6 col-sm-12 mt-2"><canvas id="dailyLine" height="180"></canvas></div>
        <div class="col-md-6 col-sm-12 mt-2"><canvas id="subjectBar" height="180"></canvas></div>
        <div class="col-md-12 mt-2"><canvas id="classStacked" height="200"></canvas></div>
    </div>

    <div class="mt-3 text-center">
        <a href="{{ route('admin.academic.assignments.index') }}" class="btn btn-primary btn-lg rounded-pill">
            <i class="bx bx-list-ul me-1"></i> View All Assignments
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Status Pie Chart
new Chart(document.getElementById('statusPie'), {
    type: 'pie',
    data: {
        labels: ['Pending','Completed','Delayed'],
        datasets:[{
            data:[{{ $pending }}, {{ $completed }}, {{ $delayed }}],
            backgroundColor:['#ffc107','#198754','#dc3545']
        }]
    },
    options:{responsive:true}
});

// Monthly Bar Chart
new Chart(document.getElementById('monthlyBar'), {
    type:'bar',
    data:{
        labels:{!! json_encode($months) !!},
        datasets:[
            {label:'Assigned', data:{!! json_encode($assignedPerMonth) !!}, backgroundColor:'#0d6efd'},
            {label:'Completed', data:{!! json_encode($completedPerMonth) !!}, backgroundColor:'#198754'},
            {label:'Delayed', data:{!! json_encode($delayedPerMonth) !!}, backgroundColor:'#dc3545'}
        ]
    },
    options:{responsive:true, plugins:{legend:{position:'top'}}}
});

// Daily Line Chart
new Chart(document.getElementById('dailyLine'), {
    type:'line',
    data:{
        labels:{!! json_encode($dailyLabels) !!},
        datasets:[{
            label:'Assignments Assigned',
            data:{!! json_encode($dailyData) !!},
            borderColor:'#0d6efd',
            fill:false,
            tension:0.3
        }]
    },
    options:{responsive:true, plugins:{legend:{display:true}}}
});

// Subject-wise Horizontal Bar
new Chart(document.getElementById('subjectBar'), {
    type:'bar',
    data:{
        labels:{!! json_encode($subjectCount->keys()->toArray()) !!},
        datasets:[{
            label:'Assignments Count',
            data:{!! json_encode($subjectCount->values()->toArray()) !!},
            backgroundColor:'#ffc107'
        }]
    },
    options:{indexAxis:'y', responsive:true}
});

// Class-wise Stacked Bar
const classLabels = {!! json_encode(array_keys($classStatus)) !!};
const pendingData = {!! json_encode(array_map(fn($v)=>$v['pending'],$classStatus)) !!};
const completedData = {!! json_encode(array_map(fn($v)=>$v['completed'],$classStatus)) !!};
const delayedData = {!! json_encode(array_map(fn($v)=>$v['delayed'],$classStatus)) !!};

new Chart(document.getElementById('classStacked'), {
    type:'bar',
    data:{
        labels:classLabels,
        datasets:[
            {label:'Pending', data:pendingData, backgroundColor:'#ffc107'},
            {label:'Completed', data:completedData, backgroundColor:'#198754'},
            {label:'Delayed', data:delayedData, backgroundColor:'#dc3545'}
        ]
    },
    options:{responsive:true, plugins:{legend:{position:'top'}}, scales:{x:{stacked:true},y:{stacked:true}}}
});
</script>
@endsection
