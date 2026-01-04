<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Models\User;
use App\Models\School;

class SuperAdminSettingsController extends Controller
{
    public function index()
    {
        $settings = $this->getAllSettings();
        return view('superadmin.settings.index', compact('settings'));
    }

    // General Settings
    public function general()
    {
        $settings = $this->getSettingsByCategory('general');
        return view('superadmin.settings.general', compact('settings'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:512',
            'system_title' => 'required|string|max:255',
            'footer_text' => 'nullable|string|max:500',
            'default_language' => 'required|string|max:10',
            'timezone' => 'required|string|max:50',
            'currency' => 'required|string|max:10',
            'currency_symbol' => 'required|string|max:10',
        ]);

        $this->updateSettings([
            'app_name' => $request->app_name,
            'system_title' => $request->system_title,
            'footer_text' => $request->footer_text,
            'default_language' => $request->default_language,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'currency_symbol' => $request->currency_symbol,
        ], 'general');

        // Handle file uploads
        if ($request->hasFile('app_logo')) {
            $logoPath = $request->file('app_logo')->store('settings', 'public');
            $this->updateSetting('app_logo', $logoPath, 'general');
        }

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            $this->updateSetting('favicon', $faviconPath, 'general');
        }

        return redirect()->back()->with('success', 'General settings updated successfully.');
    }

    // User & Roles Settings
    public function users()
    {
        $superAdmins = User::where('role_id', 1)->get();
        $admins = User::where('role_id', 2)->with('managedSchool')->get();
        $settings = $this->getSettingsByCategory('users');
        return view('superadmin.settings.users', compact('superAdmins', 'admins', 'settings'));
    }

    public function updateUsers(Request $request)
    {
        $request->validate([
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'session_timeout' => 'required|integer|min:30|max:1440',
            'two_factor_enabled' => 'boolean',
            'password_min_length' => 'required|integer|min:6|max:32',
            'password_require_special' => 'boolean',
        ]);

        $this->updateSettings([
            'max_login_attempts' => $request->max_login_attempts,
            'session_timeout' => $request->session_timeout,
            'two_factor_enabled' => $request->has('two_factor_enabled'),
            'password_min_length' => $request->password_min_length,
            'password_require_special' => $request->has('password_require_special'),
        ], 'users');

        return redirect()->back()->with('success', 'User settings updated successfully.');
    }

    // Email & Notification Settings
    public function email()
    {
        $settings = $this->getSettingsByCategory('email');
        return view('superadmin.settings.email', compact('settings'));
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:50',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'sms_api_key' => 'nullable|string|max:255',
            'push_notification_key' => 'nullable|string|max:255',
        ]);

        $this->updateSettings([
            'mail_driver' => $request->mail_driver,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
            'sms_api_key' => $request->sms_api_key,
            'push_notification_key' => $request->push_notification_key,
        ], 'email');

        return redirect()->back()->with('success', 'Email settings updated successfully.');
    }

    // System Configuration
    public function system()
    {
        $settings = $this->getSettingsByCategory('system');
        $systemInfo = $this->getSystemInfo();
        return view('superadmin.settings.system', compact('settings', 'systemInfo'));
    }

    public function updateSystem(Request $request)
    {
        $request->validate([
            'app_env' => 'required|in:local,staging,production',
            'debug_mode' => 'boolean',
            'log_level' => 'required|in:debug,info,warning,error',
            'maintenance_mode' => 'boolean',
            'stripe_key' => 'nullable|string|max:255',
            'stripe_secret' => 'nullable|string|max:255',
            'razorpay_key' => 'nullable|string|max:255',
            'razorpay_secret' => 'nullable|string|max:255',
            'google_api_key' => 'nullable|string|max:255',
            'openai_api_key' => 'nullable|string|max:255',
        ]);

        $this->updateSettings([
            'app_env' => $request->app_env,
            'debug_mode' => $request->has('debug_mode'),
            'log_level' => $request->log_level,
            'maintenance_mode' => $request->has('maintenance_mode'),
            'stripe_key' => $request->stripe_key,
            'stripe_secret' => $request->stripe_secret,
            'razorpay_key' => $request->razorpay_key,
            'razorpay_secret' => $request->razorpay_secret,
            'google_api_key' => $request->google_api_key,
            'openai_api_key' => $request->openai_api_key,
        ], 'system');

        return redirect()->back()->with('success', 'System settings updated successfully.');
    }

    // Database & Backup Settings
    public function database()
    {
        $settings = $this->getSettingsByCategory('database');
        $backupFiles = $this->getBackupFiles();
        return view('superadmin.settings.database', compact('settings', 'backupFiles'));
    }

    public function updateDatabase(Request $request)
    {
        $request->validate([
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'backup_retention_days' => 'required|integer|min:1|max:365',
            'storage_disk' => 'required|in:local,public,s3',
            's3_bucket' => 'nullable|string|max:255',
            's3_region' => 'nullable|string|max:50',
        ]);

        $this->updateSettings([
            'backup_frequency' => $request->backup_frequency,
            'backup_retention_days' => $request->backup_retention_days,
            'storage_disk' => $request->storage_disk,
            's3_bucket' => $request->s3_bucket,
            's3_region' => $request->s3_region,
        ], 'database');

        return redirect()->back()->with('success', 'Database settings updated successfully.');
    }

    public function createBackup()
    {
        try {
            Artisan::call('backup:run');
            return redirect()->back()->with('success', 'Database backup created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    // Security Settings
    public function security()
    {
        $settings = $this->getSettingsByCategory('security');
        return view('superadmin.settings.security', compact('settings'));
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'force_https' => 'boolean',
            'password_expiry_days' => 'nullable|integer|min:30|max:365',
            'ip_whitelist' => 'nullable|string',
            'csrf_protection' => 'boolean',
            'xss_protection' => 'boolean',
            'audit_log_enabled' => 'boolean',
        ]);

        $this->updateSettings([
            'force_https' => $request->has('force_https'),
            'password_expiry_days' => $request->password_expiry_days,
            'ip_whitelist' => $request->ip_whitelist,
            'csrf_protection' => $request->has('csrf_protection'),
            'xss_protection' => $request->has('xss_protection'),
            'audit_log_enabled' => $request->has('audit_log_enabled'),
        ], 'security');

        return redirect()->back()->with('success', 'Security settings updated successfully.');
    }

    // Payment Settings
    public function payment()
    {
        $settings = $this->getSettingsByCategory('payment');
        return view('superadmin.settings.payment', compact('settings'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'default_payment_gateway' => 'required|in:stripe,razorpay,paypal',
            'stripe_webhook_secret' => 'nullable|string|max:255',
            'paypal_client_id' => 'nullable|string|max:255',
            'paypal_client_secret' => 'nullable|string|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency_code' => 'required|string|max:3',
        ]);

        $this->updateSettings([
            'default_payment_gateway' => $request->default_payment_gateway,
            'stripe_webhook_secret' => $request->stripe_webhook_secret,
            'paypal_client_id' => $request->paypal_client_id,
            'paypal_client_secret' => $request->paypal_client_secret,
            'tax_rate' => $request->tax_rate,
            'currency_code' => $request->currency_code,
        ], 'payment');

        return redirect()->back()->with('success', 'Payment settings updated successfully.');
    }

    // Developer Tools
    public function developer()
    {
        $settings = $this->getSettingsByCategory('developer');
        $systemInfo = $this->getSystemInfo();
        $logs = $this->getRecentLogs();
        return view('superadmin.settings.developer', compact('settings', 'systemInfo', 'logs'));
    }

    public function updateDeveloper(Request $request)
    {
        $request->validate([
            'api_documentation_enabled' => 'boolean',
            'webhook_url' => 'nullable|url|max:255',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);

        $this->updateSettings([
            'api_documentation_enabled' => $request->has('api_documentation_enabled'),
            'webhook_url' => $request->webhook_url,
            'custom_css' => $request->custom_css,
            'custom_js' => $request->custom_js,
        ], 'developer');

        return redirect()->back()->with('success', 'Developer settings updated successfully.');
    }

    // UI/Theme Settings
    public function theme()
    {
        $settings = $this->getSettingsByCategory('theme');
        return view('superadmin.settings.theme', compact('settings'));
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme_mode' => 'required|in:light,dark,auto',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'sidebar_style' => 'required|in:default,compact,icon',
            'font_family' => 'required|string|max:100',
        ]);

        $this->updateSettings([
            'theme_mode' => $request->theme_mode,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'sidebar_style' => $request->sidebar_style,
            'font_family' => $request->font_family,
        ], 'theme');

        return redirect()->back()->with('success', 'Theme settings updated successfully.');
    }

    // Advanced Settings
    public function advanced()
    {
        $settings = $this->getSettingsByCategory('advanced');
        return view('superadmin.settings.advanced', compact('settings'));
    }

    public function updateAdvanced(Request $request)
    {
        $request->validate([
            'multi_tenancy_enabled' => 'boolean',
            'data_retention_days' => 'nullable|integer|min:30|max:3650',
            'gdpr_compliance' => 'boolean',
            'privacy_policy_url' => 'nullable|url|max:255',
            'terms_of_service_url' => 'nullable|url|max:255',
        ]);

        $this->updateSettings([
            'multi_tenancy_enabled' => $request->has('multi_tenancy_enabled'),
            'data_retention_days' => $request->data_retention_days,
            'gdpr_compliance' => $request->has('gdpr_compliance'),
            'privacy_policy_url' => $request->privacy_policy_url,
            'terms_of_service_url' => $request->terms_of_service_url,
        ], 'advanced');

        return redirect()->back()->with('success', 'Advanced settings updated successfully.');
    }

    // System Actions
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            return redirect()->back()->with('success', 'All caches cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    // Export Settings
    public function exportSettings(Request $request)
    {
        $format = $request->get('format', 'json');
        $category = $request->get('category', 'all');
        
        try {
            $settings = $this->getSettingsForExport($category);
            
            switch ($format) {
                case 'json':
                    return $this->exportToJson($settings);
                case 'csv':
                    return $this->exportToCsv($settings);
                case 'excel':
                    return $this->exportToExcel($settings);
                default:
                    return redirect()->back()->with('error', 'Invalid export format.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to export settings: ' . $e->getMessage());
        }
    }

    public function importSettings(Request $request)
    {
        $request->validate([
            'settings_file' => 'required|file|mimes:json,csv,xlsx|max:2048',
        ]);

        try {
            $file = $request->file('settings_file');
            $extension = $file->getClientOriginalExtension();
            
            switch ($extension) {
                case 'json':
                    $this->importFromJson($file);
                    break;
                case 'csv':
                    $this->importFromCsv($file);
                    break;
                case 'xlsx':
                    $this->importFromExcel($file);
                    break;
                default:
                    return redirect()->back()->with('error', 'Unsupported file format.');
            }
            
            return redirect()->back()->with('success', 'Settings imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to import settings: ' . $e->getMessage());
        }
    }

    public function toggleMaintenance()
    {
        try {
            $isDown = Artisan::call('down');
            if ($isDown === 0) {
                return redirect()->back()->with('success', 'Maintenance mode enabled.');
            } else {
                Artisan::call('up');
                return redirect()->back()->with('success', 'Maintenance mode disabled.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to toggle maintenance mode: ' . $e->getMessage());
        }
    }

    // Helper Methods
    private function getAllSettings()
    {
        return Setting::all()->groupBy('category');
    }

    private function getSettingsByCategory($category)
    {
        return Setting::where('category', $category)->pluck('value', 'key')->toArray();
    }

    private function updateSettings($settings, $category)
    {
        foreach ($settings as $key => $value) {
            $this->updateSetting($key, $value, $category);
        }
    }

    private function updateSetting($key, $value, $category)
    {
        Setting::updateOrCreate(
            ['key' => $key, 'category' => $category],
            ['value' => $value]
        );
    }

    private function getSystemInfo()
    {
        return [
            'app_version' => config('app.version', '1.0.0'),
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_version' => DB::select('SELECT VERSION() as version')[0]->version ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];
    }

    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');
        if (!File::exists($backupPath)) {
            return [];
        }
        
        return collect(File::files($backupPath))
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'created' => $file->getMTime(),
                ];
            })
            ->sortByDesc('created')
            ->take(10)
            ->values();
    }

    private function getRecentLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        if (!File::exists($logFile)) {
            return [];
        }

        $logs = File::get($logFile);
        $logLines = explode("\n", $logs);
        return array_slice(array_reverse($logLines), 0, 50);
    }

    // Export Helper Methods
    private function getSettingsForExport($category)
    {
        if ($category === 'all') {
            return Setting::all()->groupBy('category');
        }
        
        return Setting::where('category', $category)->get()->groupBy('category');
    }

    private function exportToJson($settings)
    {
        $exportData = [
            'export_info' => [
                'exported_at' => now()->toISOString(),
                'app_name' => config('app.name'),
                'app_version' => config('app.version', '1.0.0'),
                'total_settings' => $settings->flatten()->count(),
            ],
            'settings' => $settings->toArray()
        ];

        $filename = 'settings_export_' . now()->format('Y_m_d_H_i_s') . '.json';
        
        return response()->json($exportData)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Content-Type', 'application/json');
    }

    private function exportToCsv($settings)
    {
        $filename = 'settings_export_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($settings) {
            $file = fopen('php://output', 'w');
            
            // Add export info
            fputcsv($file, ['Export Info']);
            fputcsv($file, ['Exported At', now()->toISOString()]);
            fputcsv($file, ['App Name', config('app.name')]);
            fputcsv($file, ['App Version', config('app.version', '1.0.0')]);
            fputcsv($file, ['Total Settings', $settings->flatten()->count()]);
            fputcsv($file, []); // Empty row
            
            // Add settings headers
            fputcsv($file, ['Settings Data']);
            fputcsv($file, ['Key', 'Value', 'Category', 'Type', 'Description', 'Is Public', 'Created At', 'Updated At']);
            
            // Add settings data
            foreach ($settings->flatten() as $setting) {
                fputcsv($file, [
                    $setting->key,
                    $setting->value,
                    $setting->category,
                    $setting->type,
                    $setting->description,
                    $setting->is_public ? 'Yes' : 'No',
                    $setting->created_at,
                    $setting->updated_at,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToExcel($settings)
    {
        $filename = 'settings_export_' . now()->format('Y_m_d_H_i_s') . '.xlsx';
        
        // For Excel export, we'll use a simple approach with CSV headers
        // In a real implementation, you might want to use Laravel Excel package
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($settings) {
            $file = fopen('php://output', 'w');
            
            // Add export info
            fputcsv($file, ['Export Info']);
            fputcsv($file, ['Exported At', now()->toISOString()]);
            fputcsv($file, ['App Name', config('app.name')]);
            fputcsv($file, ['App Version', config('app.version', '1.0.0')]);
            fputcsv($file, ['Total Settings', $settings->flatten()->count()]);
            fputcsv($file, []); // Empty row
            
            // Add settings headers
            fputcsv($file, ['Settings Data']);
            fputcsv($file, ['Key', 'Value', 'Category', 'Type', 'Description', 'Is Public', 'Created At', 'Updated At']);
            
            // Add settings data
            foreach ($settings->flatten() as $setting) {
                fputcsv($file, [
                    $setting->key,
                    $setting->value,
                    $setting->category,
                    $setting->type,
                    $setting->description,
                    $setting->is_public ? 'Yes' : 'No',
                    $setting->created_at,
                    $setting->updated_at,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Import Helper Methods
    private function importFromJson($file)
    {
        $content = file_get_contents($file->getPathname());
        $data = json_decode($content, true);
        
        if (!$data || !isset($data['settings'])) {
            throw new \Exception('Invalid JSON format.');
        }
        
        $imported = 0;
        foreach ($data['settings'] as $category => $settings) {
            foreach ($settings as $setting) {
                Setting::updateOrCreate(
                    ['key' => $setting['key']],
                    [
                        'value' => $setting['value'],
                        'category' => $setting['category'],
                        'type' => $setting['type'],
                        'description' => $setting['description'],
                        'is_public' => $setting['is_public'],
                    ]
                );
                $imported++;
            }
        }
        
        return $imported;
    }

    private function importFromCsv($file)
    {
        $handle = fopen($file->getPathname(), 'r');
        $imported = 0;
        $isDataSection = false;
        
        while (($data = fgetcsv($handle)) !== false) {
            if ($data[0] === 'Settings Data') {
                $isDataSection = true;
                fgetcsv($handle); // Skip header row
                continue;
            }
            
            if ($isDataSection && count($data) >= 8) {
                Setting::updateOrCreate(
                    ['key' => $data[0]],
                    [
                        'value' => $data[1],
                        'category' => $data[2],
                        'type' => $data[3],
                        'description' => $data[4],
                        'is_public' => $data[5] === 'Yes',
                    ]
                );
                $imported++;
            }
        }
        
        fclose($handle);
        return $imported;
    }

    private function importFromExcel($file)
    {
        // For Excel import, we'll treat it similar to CSV
        // In a real implementation, you might want to use Laravel Excel package
        return $this->importFromCsv($file);
    }
}
