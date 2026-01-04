@extends('superadmin.app')

@section('title', 'UI/Theme Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-palette fs-1 text-info"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">UI/Theme Settings</h1>
                            <p class="text-muted mb-0">Theme customization, colors, fonts, and UI preferences</p>
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
                                <i class="bx bx-palette me-2 text-info"></i>Theme Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.theme.update') }}" method="POST">
                                @csrf
                                
                                <!-- Theme Mode -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-moon me-2"></i>Theme Mode
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="theme_mode" class="form-label fw-bold">Theme Mode</label>
                                        <select class="form-select @error('theme_mode') is-invalid @enderror" 
                                                id="theme_mode" name="theme_mode" required>
                                            <option value="light" {{ ($settings['theme_mode'] ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                                            <option value="dark" {{ ($settings['theme_mode'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                                            <option value="auto" {{ ($settings['theme_mode'] ?? '') == 'auto' ? 'selected' : '' }}>Auto</option>
                                        </select>
                                        @error('theme_mode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Color Scheme -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-palette me-2"></i>Color Scheme
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="primary_color" class="form-label fw-bold">Primary Color</label>
                                        <input type="color" class="form-control form-control-color @error('primary_color') is-invalid @enderror" 
                                               id="primary_color" name="primary_color" 
                                               value="{{ $settings['primary_color'] ?? '#007bff' }}" required>
                                        @error('primary_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="secondary_color" class="form-label fw-bold">Secondary Color</label>
                                        <input type="color" class="form-control form-control-color @error('secondary_color') is-invalid @enderror" 
                                               id="secondary_color" name="secondary_color" 
                                               value="{{ $settings['secondary_color'] ?? '#6c757d' }}" required>
                                        @error('secondary_color')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Layout Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-layout me-2"></i>Layout Settings
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="sidebar_style" class="form-label fw-bold">Sidebar Style</label>
                                        <select class="form-select @error('sidebar_style') is-invalid @enderror" 
                                                id="sidebar_style" name="sidebar_style" required>
                                            <option value="default" {{ ($settings['sidebar_style'] ?? 'default') == 'default' ? 'selected' : '' }}>Default</option>
                                            <option value="compact" {{ ($settings['sidebar_style'] ?? '') == 'compact' ? 'selected' : '' }}>Compact</option>
                                            <option value="icon" {{ ($settings['sidebar_style'] ?? '') == 'icon' ? 'selected' : '' }}>Icon Only</option>
                                        </select>
                                        @error('sidebar_style')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="font_family" class="form-label fw-bold">Font Family</label>
                                        <select class="form-select @error('font_family') is-invalid @enderror" 
                                                id="font_family" name="font_family" required>
                                            <option value="Inter" {{ ($settings['font_family'] ?? 'Inter') == 'Inter' ? 'selected' : '' }}>Inter</option>
                                            <option value="Roboto" {{ ($settings['font_family'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                                            <option value="Open Sans" {{ ($settings['font_family'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                            <option value="Lato" {{ ($settings['font_family'] ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                                            <option value="Poppins" {{ ($settings['font_family'] ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                                        </select>
                                        @error('font_family')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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

                <!-- Theme Preview -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-show me-2 text-success"></i>Theme Preview
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="theme-preview">
                                <div class="preview-header mb-3" style="background: {{ $settings['primary_color'] ?? '#007bff' }}; color: white; padding: 1rem; border-radius: 8px;">
                                    <h6 class="mb-0">Header Preview</h6>
                                </div>
                                <div class="preview-sidebar mb-3" style="background: {{ $settings['secondary_color'] ?? '#6c757d' }}; color: white; padding: 1rem; border-radius: 8px;">
                                    <h6 class="mb-0">Sidebar Preview</h6>
                                </div>
                                <div class="preview-content" style="border: 2px solid {{ $settings['primary_color'] ?? '#007bff' }}; padding: 1rem; border-radius: 8px;">
                                    <h6 class="mb-0">Content Preview</h6>
                                </div>
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
                                    <strong>Mode:</strong> 
                                    <span class="text-primary">{{ $settings['theme_mode'] ?? 'light' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Primary:</strong> 
                                    <span class="badge" style="background-color: {{ $settings['primary_color'] ?? '#007bff' }}; color: white;">
                                        {{ $settings['primary_color'] ?? '#007bff' }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>Secondary:</strong> 
                                    <span class="badge" style="background-color: {{ $settings['secondary_color'] ?? '#6c757d' }}; color: white;">
                                        {{ $settings['secondary_color'] ?? '#6c757d' }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <strong>Sidebar:</strong> 
                                    <span class="text-primary">{{ $settings['sidebar_style'] ?? 'default' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Font:</strong> 
                                    <span class="text-primary">{{ $settings['font_family'] ?? 'Inter' }}</span>
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

.form-control-color {
    width: 100%;
    height: 50px;
    border-radius: 8px;
    border: 1px solid #e1e5e9;
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

.theme-preview {
    font-family: {{ $settings['font_family'] ?? 'Inter' }}, sans-serif;
}

.list-unstyled li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.list-unstyled li:last-child {
    border-bottom: none;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>
@endsection
