@extends('librarian.layout.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">General Settings</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('librarian.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Settings</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Preferences
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('librarian.settings.update') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="language">Language</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="en" selected>English</option>
                                        <option value="hi">Hindi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="timezone">Timezone</label>
                                    <select class="form-select" id="timezone" name="timezone">
                                        <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                                        <option value="UTC">UTC</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label" for="theme">Theme</label>
                                    <select class="form-select" id="theme" name="theme">
                                        <option value="light" selected>Light</option>
                                        <option value="dark">Dark</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Notifications</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" checked>
                                        <label class="form-check-label" for="email_notifications">Email notifications</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications">
                                        <label class="form-check-label" for="sms_notifications">SMS notifications</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="signature">Signature (for messages)</label>
                            <input type="text" id="signature" name="signature" class="form-control" placeholder="Best regards, Librarian">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('librarian.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Notification Preferences
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_overdue" checked>
                                <label class="form-check-label" for="notify_overdue">Overdue book alerts</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_returns" checked>
                                <label class="form-check-label" for="notify_returns">Return confirmations</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_new_books">
                                <label class="form-check-label" for="notify_new_books">New books added</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="notify_activity">
                                <label class="form-check-label" for="notify_activity">Weekly activity summary</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>About Your Account
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><strong>Name:</strong> {{ auth()->user()->name }}</li>
                        <li class="mb-2"><strong>Email:</strong> {{ auth()->user()->email }}</li>
                        <li class="mb-2"><strong>Role:</strong> Librarian</li>
                        <li class="mb-0"><strong>Member since:</strong> {{ auth()->user()->created_at->format('d M Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 0.5rem;
}
.form-actions { padding-top: 1rem; border-top: 1px solid #e9ecef; margin-top: 1rem; }
</style>
@endsection


