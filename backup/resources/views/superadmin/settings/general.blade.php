@extends('superadmin.app')

@section('title', 'General Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-cog fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">General Settings</h1>
                            <p class="text-muted mb-0">Configure basic application settings</p>
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
                                <i class="bx bx-cog me-2 text-primary"></i>Application Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <!-- App Name & Logo -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="app_name" class="form-label fw-bold">Application Name</label>
                                        <input type="text" class="form-control @error('app_name') is-invalid @enderror" 
                                               id="app_name" name="app_name" 
                                               value="{{ $settings['app_name'] ?? config('app.name') }}" required>
                                        @error('app_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="system_title" class="form-label fw-bold">System Title</label>
                                        <input type="text" class="form-control @error('system_title') is-invalid @enderror" 
                                               id="system_title" name="system_title" 
                                               value="{{ $settings['system_title'] ?? 'School Management System' }}" required>
                                        @error('system_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Logo Upload -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="app_logo" class="form-label fw-bold">Application Logo</label>
                                        <input type="file" class="form-control @error('app_logo') is-invalid @enderror" 
                                               id="app_logo" name="app_logo" accept="image/*">
                                        @error('app_logo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Recommended size: 200x60px, Max size: 2MB</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="favicon" class="form-label fw-bold">Favicon</label>
                                        <input type="file" class="form-control @error('favicon') is-invalid @enderror" 
                                               id="favicon" name="favicon" accept="image/*">
                                        @error('favicon')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Recommended size: 32x32px, Max size: 512KB</small>
                                    </div>
                                </div>

                                <!-- Footer Text -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <label for="footer_text" class="form-label fw-bold">Footer Text</label>
                                        <textarea class="form-control @error('footer_text') is-invalid @enderror" 
                                                  id="footer_text" name="footer_text" rows="3" 
                                                  placeholder="Enter footer text...">{{ $settings['footer_text'] ?? '' }}</textarea>
                                        @error('footer_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Language & Locale -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="default_language" class="form-label fw-bold">Default Language</label>
                                        <select class="form-select @error('default_language') is-invalid @enderror" 
                                                id="default_language" name="default_language" required>
                                            <option value="en" {{ ($settings['default_language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                            <option value="es" {{ ($settings['default_language'] ?? '') == 'es' ? 'selected' : '' }}>Spanish</option>
                                            <option value="fr" {{ ($settings['default_language'] ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                                            <option value="de" {{ ($settings['default_language'] ?? '') == 'de' ? 'selected' : '' }}>German</option>
                                            <option value="it" {{ ($settings['default_language'] ?? '') == 'it' ? 'selected' : '' }}>Italian</option>
                                            <option value="pt" {{ ($settings['default_language'] ?? '') == 'pt' ? 'selected' : '' }}>Portuguese</option>
                                            <option value="ru" {{ ($settings['default_language'] ?? '') == 'ru' ? 'selected' : '' }}>Russian</option>
                                            <option value="zh" {{ ($settings['default_language'] ?? '') == 'zh' ? 'selected' : '' }}>Chinese</option>
                                            <option value="ja" {{ ($settings['default_language'] ?? '') == 'ja' ? 'selected' : '' }}>Japanese</option>
                                            <option value="ko" {{ ($settings['default_language'] ?? '') == 'ko' ? 'selected' : '' }}>Korean</option>
                                        </select>
                                        @error('default_language')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="timezone" class="form-label fw-bold">Timezone</label>
                                        <select class="form-select @error('timezone') is-invalid @enderror" 
                                                id="timezone" name="timezone" required>
                                            <option value="UTC" {{ ($settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                            <option value="America/New_York" {{ ($settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                                            <option value="America/Chicago" {{ ($settings['timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>Central Time (CT)</option>
                                            <option value="America/Denver" {{ ($settings['timezone'] ?? '') == 'America/Denver' ? 'selected' : '' }}>Mountain Time (MT)</option>
                                            <option value="America/Los_Angeles" {{ ($settings['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                                            <option value="Europe/London" {{ ($settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>London (GMT)</option>
                                            <option value="Europe/Paris" {{ ($settings['timezone'] ?? '') == 'Europe/Paris' ? 'selected' : '' }}>Paris (CET)</option>
                                            <option value="Europe/Berlin" {{ ($settings['timezone'] ?? '') == 'Europe/Berlin' ? 'selected' : '' }}>Berlin (CET)</option>
                                            <option value="Asia/Tokyo" {{ ($settings['timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo (JST)</option>
                                            <option value="Asia/Shanghai" {{ ($settings['timezone'] ?? '') == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai (CST)</option>
                                            <option value="Asia/Kolkata" {{ ($settings['timezone'] ?? '') == 'Asia/Kolkata' ? 'selected' : '' }}>Mumbai (IST)</option>
                                            <option value="Australia/Sydney" {{ ($settings['timezone'] ?? '') == 'Australia/Sydney' ? 'selected' : '' }}>Sydney (AEST)</option>
                                        </select>
                                        @error('timezone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Currency Settings -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="currency" class="form-label fw-bold">Currency</label>
                                        <select class="form-select @error('currency') is-invalid @enderror" 
                                                id="currency" name="currency" required>
                                            <option value="USD" {{ ($settings['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>US Dollar (USD)</option>
                                            <option value="EUR" {{ ($settings['currency'] ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                            <option value="GBP" {{ ($settings['currency'] ?? '') == 'GBP' ? 'selected' : '' }}>British Pound (GBP)</option>
                                            <option value="JPY" {{ ($settings['currency'] ?? '') == 'JPY' ? 'selected' : '' }}>Japanese Yen (JPY)</option>
                                            <option value="CAD" {{ ($settings['currency'] ?? '') == 'CAD' ? 'selected' : '' }}>Canadian Dollar (CAD)</option>
                                            <option value="AUD" {{ ($settings['currency'] ?? '') == 'AUD' ? 'selected' : '' }}>Australian Dollar (AUD)</option>
                                            <option value="CHF" {{ ($settings['currency'] ?? '') == 'CHF' ? 'selected' : '' }}>Swiss Franc (CHF)</option>
                                            <option value="CNY" {{ ($settings['currency'] ?? '') == 'CNY' ? 'selected' : '' }}>Chinese Yuan (CNY)</option>
                                            <option value="INR" {{ ($settings['currency'] ?? '') == 'INR' ? 'selected' : '' }}>Indian Rupee (INR)</option>
                                            <option value="BRL" {{ ($settings['currency'] ?? '') == 'BRL' ? 'selected' : '' }}>Brazilian Real (BRL)</option>
                                        </select>
                                        @error('currency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="currency_symbol" class="form-label fw-bold">Currency Symbol</label>
                                        <input type="text" class="form-control @error('currency_symbol') is-invalid @enderror" 
                                               id="currency_symbol" name="currency_symbol" 
                                               value="{{ $settings['currency_symbol'] ?? '$' }}" required>
                                        @error('currency_symbol')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Preview Panel -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-show me-2 text-info"></i>Preview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="preview-section mb-4">
                                <h6 class="fw-bold text-muted mb-2">Application Logo</h6>
                                <div class="logo-preview border rounded p-3 text-center">
                                    <img src="{{ asset('storage/' . ($settings['app_logo'] ?? 'default-logo.png')) }}" 
                                         alt="App Logo" class="img-fluid" style="max-height: 60px;" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div style="display: none;" class="text-muted">No logo uploaded</div>
                                </div>
                            </div>

                            <div class="preview-section mb-4">
                                <h6 class="fw-bold text-muted mb-2">Favicon</h6>
                                <div class="favicon-preview border rounded p-3 text-center">
                                    <img src="{{ asset('storage/' . ($settings['favicon'] ?? 'default-favicon.ico')) }}" 
                                         alt="Favicon" class="img-fluid" style="max-height: 32px;" 
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div style="display: none;" class="text-muted">No favicon uploaded</div>
                                </div>
                            </div>

                            <div class="preview-section">
                                <h6 class="fw-bold text-muted mb-2">Current Settings</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>App Name:</strong> 
                                        <span id="preview-app-name">{{ $settings['app_name'] ?? config('app.name') }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Language:</strong> 
                                        <span id="preview-language">{{ $settings['default_language'] ?? 'en' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Timezone:</strong> 
                                        <span id="preview-timezone">{{ $settings['timezone'] ?? 'UTC' }}</span>
                                    </li>
                                    <li class="mb-2">
                                        <strong>Currency:</strong> 
                                        <span id="preview-currency">{{ $settings['currency'] ?? 'USD' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.settings-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
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
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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

.preview-section {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.preview-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.logo-preview, .favicon-preview {
    background: #f8f9fa;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
// Update preview when form inputs change
document.addEventListener('DOMContentLoaded', function() {
    const inputs = ['app_name', 'default_language', 'timezone', 'currency'];
    
    inputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById('preview-' + inputId.replace('_', '-'));
        
        if (input && preview) {
            input.addEventListener('change', function() {
                preview.textContent = this.value;
            });
        }
    });
});
</script>
@endsection
