@extends('superadmin.app')

@section('title', 'Database & Backup Settings')

@section('content')
<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <div class="settings-icon me-3">
                            <i class="bx bx-data fs-1 text-secondary"></i>
                        </div>
                        <div>
                            <h1 class="m-0 text-dark fw-bold">Database & Backup Settings</h1>
                            <p class="text-muted mb-0">Backup settings, storage configuration, and data management</p>
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
                                <i class="bx bx-data me-2 text-secondary"></i>Backup Configuration
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('superadmin.settings.database.update') }}" method="POST">
                                @csrf
                                
                                <!-- Backup Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-calendar me-2"></i>Backup Schedule
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="backup_frequency" class="form-label fw-bold">Backup Frequency</label>
                                        <select class="form-select @error('backup_frequency') is-invalid @enderror" 
                                                id="backup_frequency" name="backup_frequency" required>
                                            <option value="daily" {{ ($settings['backup_frequency'] ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily</option>
                                            <option value="weekly" {{ ($settings['backup_frequency'] ?? '') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                            <option value="monthly" {{ ($settings['backup_frequency'] ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        </select>
                                        @error('backup_frequency')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="backup_retention_days" class="form-label fw-bold">Retention Days</label>
                                        <input type="number" class="form-control @error('backup_retention_days') is-invalid @enderror" 
                                               id="backup_retention_days" name="backup_retention_days" 
                                               value="{{ $settings['backup_retention_days'] ?? '30' }}" 
                                               min="1" max="365" required>
                                        @error('backup_retention_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">How many days to keep backups</small>
                                    </div>
                                </div>

                                <!-- Storage Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="bx bx-hdd me-2"></i>Storage Configuration
                                        </h6>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="storage_disk" class="form-label fw-bold">Storage Disk</label>
                                        <select class="form-select @error('storage_disk') is-invalid @enderror" 
                                                id="storage_disk" name="storage_disk" required>
                                            <option value="local" {{ ($settings['storage_disk'] ?? 'local') == 'local' ? 'selected' : '' }}>Local</option>
                                            <option value="public" {{ ($settings['storage_disk'] ?? '') == 'public' ? 'selected' : '' }}>Public</option>
                                            <option value="s3" {{ ($settings['storage_disk'] ?? '') == 's3' ? 'selected' : '' }}>Amazon S3</option>
                                        </select>
                                        @error('storage_disk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="s3_bucket" class="form-label fw-bold">S3 Bucket</label>
                                        <input type="text" class="form-control @error('s3_bucket') is-invalid @enderror" 
                                               id="s3_bucket" name="s3_bucket" 
                                               value="{{ $settings['s3_bucket'] ?? '' }}">
                                        @error('s3_bucket')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Required for S3 storage</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label for="s3_region" class="form-label fw-bold">S3 Region</label>
                                        <select class="form-select @error('s3_region') is-invalid @enderror" 
                                                id="s3_region" name="s3_region">
                                            <option value="us-east-1" {{ ($settings['s3_region'] ?? 'us-east-1') == 'us-east-1' ? 'selected' : '' }}>US East (N. Virginia)</option>
                                            <option value="us-west-2" {{ ($settings['s3_region'] ?? '') == 'us-west-2' ? 'selected' : '' }}>US West (Oregon)</option>
                                            <option value="eu-west-1" {{ ($settings['s3_region'] ?? '') == 'eu-west-1' ? 'selected' : '' }}>Europe (Ireland)</option>
                                            <option value="ap-southeast-1" {{ ($settings['s3_region'] ?? '') == 'ap-southeast-1' ? 'selected' : '' }}>Asia Pacific (Singapore)</option>
                                        </select>
                                        @error('s3_region')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-secondary btn-lg">
                                        <i class="bx bx-save me-2"></i>Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Backup Management -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bx bx-data me-2 text-primary"></i>Backup Management
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2 mb-3">
                                <button class="btn btn-primary" onclick="createBackup()">
                                    <i class="bx bx-plus me-2"></i>Create Backup Now
                                </button>
                            </div>
                            
                            <h6 class="fw-bold text-muted mb-3">Recent Backups</h6>
                            <div class="backup-list">
                                @forelse($backupFiles as $backup)
                                <div class="backup-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                    <div>
                                        <small class="fw-bold">{{ $backup['name'] }}</small>
                                        <br>
                                        <small class="text-muted">{{ number_format($backup['size'] / 1024, 2) }} KB</small>
                                    </div>
                                    <div>
                                        <small class="text-muted">{{ date('M d, Y', $backup['created']) }}</small>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted py-3">
                                    <i class="bx bx-data fs-1"></i>
                                    <p class="mb-0">No backups found</p>
                                </div>
                                @endforelse
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
                                    <strong>Frequency:</strong> 
                                    <span class="text-primary">{{ $settings['backup_frequency'] ?? 'daily' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Retention:</strong> 
                                    <span class="text-primary">{{ $settings['backup_retention_days'] ?? '30' }} days</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Storage:</strong> 
                                    <span class="text-primary">{{ $settings['storage_disk'] ?? 'local' }}</span>
                                </li>
                                @if(($settings['storage_disk'] ?? 'local') == 's3')
                                <li class="mb-2">
                                    <strong>Bucket:</strong> 
                                    <span class="text-primary">{{ $settings['s3_bucket'] ?? 'Not set' }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Region:</strong> 
                                    <span class="text-primary">{{ $settings['s3_region'] ?? 'us-east-1' }}</span>
                                </li>
                                @endif
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
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
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
    border-color: #6c757d;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
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

.backup-item {
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.backup-item:hover {
    background-color: #e9ecef;
}

.list-unstyled li {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.list-unstyled li:last-child {
    border-bottom: none;
}
</style>

<script>
function createBackup() {
    if (confirm('Are you sure you want to create a database backup?')) {
        fetch('{{ route("superadmin.settings.create-backup") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Backup created successfully!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showAlert('Failed to create backup!', 'error');
            }
        })
        .catch(error => {
            showAlert('Error occurred while creating backup!', 'error');
        });
    }
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="bx bx-${type === 'success' ? 'check-circle' : 'error-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('.content').prepend(alertHtml);
    
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection
