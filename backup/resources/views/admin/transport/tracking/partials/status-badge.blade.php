@if($data && is_object($data) && isset($data->status))
    @php
        $statusConfig = [
            'on_time' => ['class' => 'bg-success', 'text' => 'On Time', 'icon' => 'fas fa-check-circle'],
            'delayed' => ['class' => 'bg-danger', 'text' => 'Delayed', 'icon' => 'fas fa-clock'],
            'early' => ['class' => 'bg-warning', 'text' => 'Early', 'icon' => 'fas fa-fast-forward'],
            'stopped' => ['class' => 'bg-secondary', 'text' => 'Stopped', 'icon' => 'fas fa-pause'],
            'moving' => ['class' => 'bg-primary', 'text' => 'Moving', 'icon' => 'fas fa-play']
        ];
        $config = $statusConfig[$data->status] ?? ['class' => 'bg-secondary', 'text' => 'Unknown', 'icon' => 'fas fa-question'];
    @endphp
    
    <span class="badge {{ $config['class'] }}">
        <i class="{{ $config['icon'] }} me-1"></i>
        {{ $config['text'] }}
    </span>
@else
    <span class="badge bg-secondary">Unknown</span>
@endif
