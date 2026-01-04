@extends('student.layout.app')

@section('title', 'Notification Settings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cog me-2"></i>Notification Settings
        </h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted">Manage your notification preferences</span>
        </div>
    </div>

    <!-- Notification Settings Form -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bell me-2"></i>Notification Preferences
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('student.notifications.settings.update') }}">
                        @csrf
                        
                        <!-- Delivery Methods -->
                        <div class="mb-4">
                            <h6 class="text-primary">Delivery Methods</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="email_notifications" value="1" 
                                               {{ old('email_notifications', $settings['email_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-envelope me-2"></i>Email Notifications
                                        </label>
                                        <small class="text-muted d-block">Receive notifications via email</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sms_notifications" value="1" 
                                               {{ old('sms_notifications', $settings['sms_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-sms me-2"></i>SMS Notifications
                                        </label>
                                        <small class="text-muted d-block">Receive notifications via SMS</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="push_notifications" value="1" 
                                               {{ old('push_notifications', $settings['push_notifications'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-mobile-alt me-2"></i>Push Notifications
                                        </label>
                                        <small class="text-muted d-block">Receive push notifications on mobile</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Types -->
                        <div class="mb-4">
                            <h6 class="text-primary">Notification Types</h6>
                            <p class="text-muted">Select which types of notifications you want to receive:</p>
                            <div class="row">
                                @if(isset($notificationTypes))
                                    @foreach($notificationTypes as $key => $label)
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notification_types[]" value="{{ $key }}" 
                                                   {{ in_array($key, old('notification_types', $settings['notification_types'] ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label">
                                                <i class="fas fa-{{ \App\Helpers\NotificationHelper::getNotificationIcon($key) }} me-2 text-{{ \App\Helpers\NotificationHelper::getNotificationColor($key) }}"></i>
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Quiet Hours -->
                        <div class="mb-4">
                            <h6 class="text-primary">Quiet Hours</h6>
                            <p class="text-muted">Set times when you don't want to receive notifications:</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Start Time</label>
                                    <input type="time" class="form-control" name="quiet_hours_start" 
                                           value="{{ old('quiet_hours_start', $settings['quiet_hours_start'] ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">End Time</label>
                                    <input type="time" class="form-control" name="quiet_hours_end" 
                                           value="{{ old('quiet_hours_end', $settings['quiet_hours_end'] ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Settings -->
                        <div class="mb-4">
                            <h6 class="text-primary">Additional Settings</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="marketing_emails" value="1" 
                                               {{ old('marketing_emails', $settings['marketing_emails'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-bullhorn me-2"></i>Marketing Emails
                                        </label>
                                        <small class="text-muted d-block">Receive promotional and marketing emails</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="weekly_digest" value="1" 
                                               {{ old('weekly_digest', $settings['weekly_digest'] ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <i class="fas fa-calendar-week me-2"></i>Weekly Digest
                                        </label>
                                        <small class="text-muted d-block">Receive a weekly summary of notifications</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Settings Info -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Settings Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">Current Settings</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Email: {{ $settings['email_notifications'] ? 'Enabled' : 'Disabled' }}</li>
                            <li><i class="fas fa-check text-success me-2"></i>SMS: {{ $settings['sms_notifications'] ? 'Enabled' : 'Disabled' }}</li>
                            <li><i class="fas fa-check text-success me-2"></i>Push: {{ $settings['push_notifications'] ? 'Enabled' : 'Disabled' }}</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Quiet Hours</h6>
                        <p class="mb-0">
                            {{ $settings['quiet_hours_start'] ? $settings['quiet_hours_start'] : 'Not set' }} - 
                            {{ $settings['quiet_hours_end'] ? $settings['quiet_hours_end'] : 'Not set' }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-primary">Enabled Types</h6>
                        <p class="mb-0">
                            {{ count($settings['notification_types'] ?? []) }} of {{ count($notificationTypes ?? []) }} types enabled
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success" onclick="enableAll()">
                            <i class="fas fa-check-circle me-2"></i>Enable All
                        </button>
                        <button class="btn btn-outline-danger" onclick="disableAll()">
                            <i class="fas fa-times-circle me-2"></i>Disable All
                        </button>
                        <button class="btn btn-outline-warning" onclick="resetToDefault()">
                            <i class="fas fa-undo me-2"></i>Reset to Default
                        </button>
                    </div>
                </div>
            </div>

            <!-- Help -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>Help
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary">Notification Types</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-book me-1 text-primary"></i>Assignment - Due dates and updates</li>
                        <li><i class="fas fa-clipboard-check me-1 text-danger"></i>Exam - Schedule and results</li>
                        <li><i class="fas fa-book-open me-1 text-info"></i>Library - Book due dates</li>
                        <li><i class="fas fa-home me-1 text-success"></i>Hostel - Maintenance and updates</li>
                        <li><i class="fas fa-credit-card me-1 text-warning"></i>Fees - Payment reminders</li>
                        <li><i class="fas fa-bus me-1 text-secondary"></i>Transport - Route updates</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Are you sure you want to reset the form? All unsaved changes will be lost.')) {
        document.querySelector('form').reset();
    }
}

function enableAll() {
    // Enable all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function disableAll() {
    // Disable all checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function resetToDefault() {
    if (confirm('Are you sure you want to reset to default settings?')) {
        // Reset to default values
        document.querySelector('input[name="email_notifications"]').checked = true;
        document.querySelector('input[name="push_notifications"]').checked = true;
        document.querySelector('input[name="sms_notifications"]').checked = false;
        document.querySelector('input[name="marketing_emails"]').checked = false;
        document.querySelector('input[name="weekly_digest"]').checked = false;
        
        // Enable all notification types
        document.querySelectorAll('input[name="notification_types[]"]').forEach(checkbox => {
            checkbox.checked = true;
        });
        
        // Reset quiet hours
        document.querySelector('input[name="quiet_hours_start"]').value = '22:00';
        document.querySelector('input[name="quiet_hours_end"]').value = '08:00';
    }
}
</script>
@endsection

