@extends('admin.layouts.app')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h4>Notifications</h4>
    <form action="{{ route('admin.notifications.readall') }}" method="POST">
      @csrf
      <button class="btn btn-sm btn-warning">Mark All as Read</button>
    </form>
  </div>
  <div class="card-body">
    @forelse($notifications as $notification)
      <div class="alert {{ $notification->read_at ? 'alert-secondary' : 'alert-info' }}">
        <strong>{{ $notification->data['title'] }}</strong><br>
        {{ $notification->data['body'] }}<br>
        <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-primary mt-2">View Plan</a>

        @if(!$notification->read_at)
        <form class="d-inline-block ms-2" method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
          @csrf
          <button class="btn btn-sm btn-outline-dark">Mark as Read</button>
        </form>
        @endif
      </div>
    @empty
      <p>No notifications yet.</p>
    @endforelse

    {{ $notifications->links() }}
  </div>
</div>
@endsection
