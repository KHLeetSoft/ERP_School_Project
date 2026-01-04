@extends('superadmin.app')

@section('title', 'Email & Notification Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-envelope fs-1 text-info"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Email & Notification Settings</h1>
                            <p class="text-muted mb-0">Configure SMTP, email templates, and notification settings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-end">
                        <a href="{{ route('superadmin.settings.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Back to Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-envelope me-2 text-info"></i>SMTP Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.email.update') }}" method="POST">
                                @csrf
                                
                                <!-- Mail Driver -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-cog me-2"></i>Mail Configuration
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mail_driver" class="form-label fw-bold">Mail Driver</label>
                                        <select class="form-select @error('mail_driver') is-invalid @enderror" 
                                                id="mail_driver" name="mail_driver" required>
                                            <option value="smtp" {{ ($settings['mail_driver'] ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                            <option value="mailgun" {{ ($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                            <option value="ses" {{ ($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                                            <option value="postmark" {{ ($settings['mail_driver'] ?? '') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                        </select>
                                        @error('mail_driver')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mail_host" class="form-label fw-bold">Mail Host</label>
                                        <input type="text" class="form-control @error('mail_host') is-invalid @enderror" 
                                               id="mail_host" name="mail_host" 
                                               value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}" required>
                                        @error('mail_host')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mail Port & Encryption -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="mail_port" class="form-label fw-bold">Port</label>
                                        <input type="number" class="form-control @error('mail_port') is-invalid @enderror" 
                                               id="mail_port" name="mail_port" 
                                               value="{{ $settings['mail_port'] ?? '587' }}" 
                                               min="1" max="65535" required>
                                        @error('mail_port')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mail_encryption" class="form-label fw-bold">Encryption</label>
                                        <select class="form-select @error('mail_encryption') is-invalid @enderror" 
                                                id="mail_encryption" name="mail_encryption">
                                            <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ ($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                            <option value="" {{ ($settings['mail_encryption'] ?? '') == '' ? 'selected' : '' }}>None</option>
                                        </select>
                                        @error('mail_encryption')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mail_username" class="form-label fw-bold">Username</label>
                                        <input type="text" class="form-control @error('mail_username') is-invalid @enderror" 
                                               id="mail_username" name="mail_username" 
                                               value="{{ $settings['mail_username'] ?? '' }}">
                                        @error('mail_username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mail Password -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="mail_password" class="form-label fw-bold">Password</label>
                                        <input type="password" class="form-control @error('mail_password') is-invalid @enderror" 
                                               id="mail_password" name="mail_password" 
                                               value="{{ $settings['mail_password'] ?? '' }}">
                                        @error('mail_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Leave empty to keep current password</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mail_from_address" class="form-label fw-bold">From Address</label>
                                        <input type="email" class="form-control @error('mail_from_address') is-invalid @enderror" 
                                               id="mail_from_address" name="mail_from_address" 
                                               value="{{ $settings['mail_from_address'] ?? 'noreply@schoolsystem.com' }}" required>
                                        @error('mail_from_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- From Name -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="mail_from_name" class="form-label fw-bold">From Name</label>
                                        <input type="text" class="form-control @error('mail_from_name') is-invalid @enderror" 
                                               id="mail_from_name" name="mail_from_name" 
                                               value="{{ $settings['mail_from_name'] ?? 'School Management System' }}" required>
                                        @error('mail_from_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- API Keys -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-key me-2"></i>API Keys
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sms_api_key" class="form-label fw-bold">SMS API Key</label>
                                        <input type="text" class="form-control @error('sms_api_key') is-invalid @enderror" 
                                               id="sms_api_key" name="sms_api_key" 
                                               value="{{ $settings['sms_api_key'] ?? '' }}">
                                        @error('sms_api_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">API key for SMS notifications</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="push_notification_key" class="form-label fw-bold">Push Notification Key</label>
                                        <input type="text" class="form-control @error('push_notification_key') is-invalid @enderror" 
                                               id="push_notification_key" name="push_notification_key" 
                                               value="{{ $settings['push_notification_key'] ?? '' }}">
                                        @error('push_notification_key')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Key for push notifications</small>
                                    </div>
                                </div>

                                <!-- Test Email -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="bx bx-info-circle me-2"></i>
                                            <strong>Test Email Configuration:</strong> After saving these settings, you can test the email configuration by sending a test email.
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-info btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Email Templates -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-file me-2 text-warning"></i>Email Templates
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-envelope me-3 text-primary"></i>
                                        <div>
                                            <h6 class="mb-1">Welcome Email</h6>
                                            <small class="text-muted">Sent to new users</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-key me-3 text-warning"></i>
                                        <div>
                                            <h6 class="mb-1">Password Reset</h6>
                                            <small class="text-muted">Password reset instructions</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-bell me-3 text-info"></i>
                                        <div>
                                            <h6 class="mb-1">Notifications</h6>
                                            <small class="text-muted">System notifications</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-calendar me-3 text-success"></i>
                                        <div>
                                            <h6 class="mb-1">Event Reminders</h6>
                                            <small class="text-muted">Event and deadline reminders</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Current Settings -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-cog me-2 text-warning"></i>Current Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <strong>Driver:</strong> 
                                    <span class="text-primary">{{ $settings['mail_driver'] ?? 'smtp' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Host:</strong> 
                                    <span class="text-primary">{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Port:</strong> 
                                    <span class="text-primary">{{ $settings['mail_port'] ?? '587' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Encryption:</strong> 
                                    <span class="text-primary">{{ $settings['mail_encryption'] ?? 'tls' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>From:</strong> 
                                    <span class="text-primary">{{ $settings['mail_from_address'] ?? 'noreply@schoolsystem.com' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.settings-icon {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
}

.form-label {
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e1e5e9;
    padding: 0.75rem;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #17a2b8;
    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
}

.btn {
    border-radius: 25px;
    padding: 0.75rem 2rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.list-group-item {
    border: none;
    padding: 1rem 0;
}

.list-group-item:not(:last-child) {
    border-bottom: 1px solid #e9ecef;
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
}
</style>
@endsection
