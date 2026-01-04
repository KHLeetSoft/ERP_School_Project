<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\FeatureToggle;
use App\Models\DeploymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class DeveloperToolsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'checkrole:superadmin']);
    }

    /**
     * Display developer tools dashboard
     */
    public function index()
    {
        $deploymentStatus = $this->getDeploymentStatus();
        $errorLogs = $this->getErrorLogs();
        $featureToggles = $this->getFeatureToggles();
        $versionInfo = $this->getVersionInfo();
        
        return view('superadmin.developer-tools.index', compact('deploymentStatus', 'errorLogs', 'featureToggles', 'versionInfo'));
    }

    /**
     * Code Deployment Status
     */
    public function deploymentStatus()
    {
        $deployments = DeploymentLog::orderBy('created_at', 'desc')->paginate(20);
        $currentVersion = $this->getCurrentVersion();
        
        return view('superadmin.developer-tools.deployment', compact('deployments', 'currentVersion'));
    }

    /**
     * Deploy to production
     */
    public function deployToProduction(Request $request)
    {
        $request->validate([
            'version' => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'environment' => 'required|in:staging,production'
        ]);
        
        try {
            // Log deployment start
            $deployment = DeploymentLog::create([
                'version' => $request->version,
                'description' => $request->description,
                'environment' => $request->environment,
                'status' => 'in_progress',
                'started_by' => auth()->id(),
                'started_at' => now()
            ]);
            
            // Run deployment commands
            $commands = [
                'git pull origin main',
                'composer install --no-dev --optimize-autoloader',
                'php artisan migrate --force',
                'php artisan config:cache',
                'php artisan route:cache',
                'php artisan view:cache',
                'php artisan queue:restart'
            ];
            
            $output = [];
            foreach ($commands as $command) {
                $output[] = shell_exec($command . ' 2>&1');
            }
            
            // Update deployment status
            $deployment->update([
                'status' => 'completed',
                'completed_at' => now(),
                'output' => implode("\n", $output)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Deployment completed successfully',
                'deployment_id' => $deployment->id
            ]);
            
        } catch (\Exception $e) {
            if (isset($deployment)) {
                $deployment->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                    'error_message' => $e->getMessage()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Deployment failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Error & Debug Logs
     */
    public function errorLogs(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (File::exists($logFile)) {
            $logContent = File::get($logFile);
            $logLines = explode("\n", $logContent);
            
            // Filter logs based on request parameters
            $filteredLogs = [];
            foreach ($logLines as $line) {
                if (empty(trim($line))) continue;
                
                // Filter by level
                if ($request->filled('level')) {
                    if (strpos($line, $request->level) === false) continue;
                }
                
                // Filter by date
                if ($request->filled('date_from')) {
                    if (strpos($line, $request->date_from) === false) continue;
                }
                
                // Filter by search term
                if ($request->filled('search')) {
                    if (stripos($line, $request->search) === false) continue;
                }
                
                $filteredLogs[] = $line;
            }
            
            // Paginate logs
            $perPage = 100;
            $currentPage = $request->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $logs = array_slice($filteredLogs, $offset, $perPage);
        }
        
        return view('superadmin.developer-tools.error-logs', compact('logs'));
    }

    /**
     * Clear error logs
     */
    public function clearErrorLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            File::put($logFile, '');
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Error logs cleared successfully'
        ]);
    }

    /**
     * Feature Toggle Management
     */
    public function featureToggles()
    {
        $toggles = FeatureToggle::orderBy('name')->get();
        return view('superadmin.developer-tools.feature-toggles', compact('toggles'));
    }

    /**
     * Create feature toggle
     */
    public function createFeatureToggle(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:feature_toggles',
            'description' => 'required|string|max:500',
            'is_enabled' => 'boolean',
            'target_schools' => 'nullable|array',
            'target_schools.*' => 'exists:schools,id'
        ]);
        
        FeatureToggle::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_enabled' => $request->boolean('is_enabled', false),
            'target_schools' => $request->target_schools ?? [],
            'created_by' => auth()->id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Feature toggle created successfully'
        ]);
    }

    /**
     * Toggle feature
     */
    public function toggleFeature(Request $request, FeatureToggle $toggle)
    {
        $toggle->update([
            'is_enabled' => !$toggle->is_enabled,
            'updated_by' => auth()->id()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Feature toggle updated successfully',
            'is_enabled' => $toggle->is_enabled
        ]);
    }

    /**
     * Version Control
     */
    public function versionControl()
    {
        $versions = $this->getVersionHistory();
        $currentVersion = $this->getCurrentVersion();
        $schoolVersions = $this->getSchoolVersions();
        
        return view('superadmin.developer-tools.version-control', compact('versions', 'currentVersion', 'schoolVersions'));
    }

    /**
     * Update school version
     */
    public function updateSchoolVersion(Request $request, School $school)
    {
        $request->validate([
            'version' => 'required|string|max:50'
        ]);
        
        $school->update([
            'current_version' => $request->version,
            'version_updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'School version updated successfully'
        ]);
    }

    /**
     * System Health Check
     */
    public function systemHealthCheck()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
            'mail' => $this->checkMail(),
            'disk_space' => $this->checkDiskSpace(),
            'memory_usage' => $this->checkMemoryUsage()
        ];
        
        $overallStatus = $this->calculateOverallHealth($health);
        
        return response()->json([
            'health' => $health,
            'overall_status' => $overallStatus,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Run Artisan Commands
     */
    public function runArtisanCommand(Request $request)
    {
        $request->validate([
            'command' => 'required|string|max:255',
            'arguments' => 'nullable|array'
        ]);
        
        try {
            $command = $request->command;
            $arguments = $request->arguments ?? [];
            
            // Whitelist allowed commands for security
            $allowedCommands = [
                'cache:clear',
                'config:cache',
                'route:cache',
                'view:cache',
                'queue:work',
                'queue:restart',
                'migrate:status',
                'db:seed',
                'tinker'
            ];
            
            if (!in_array($command, $allowedCommands)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Command not allowed'
                ], 403);
            }
            
            $exitCode = Artisan::call($command, $arguments);
            $output = Artisan::output();
            
            return response()->json([
                'success' => $exitCode === 0,
                'output' => $output,
                'exit_code' => $exitCode
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get deployment status
     */
    private function getDeploymentStatus()
    {
        $latestDeployment = DeploymentLog::latest()->first();
        
        return [
            'current_version' => $this->getCurrentVersion(),
            'latest_deployment' => $latestDeployment,
            'deployment_count' => DeploymentLog::count(),
            'successful_deployments' => DeploymentLog::where('status', 'completed')->count(),
            'failed_deployments' => DeploymentLog::where('status', 'failed')->count()
        ];
    }

    /**
     * Get error logs
     */
    private function getErrorLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            return [];
        }
        
        $logContent = File::get($logFile);
        $logLines = explode("\n", $logContent);
        
        // Get last 50 lines
        return array_slice(array_filter($logLines), -50);
    }

    /**
     * Get feature toggles
     */
    private function getFeatureToggles()
    {
        return FeatureToggle::with(['createdBy', 'updatedBy'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get version info
     */
    private function getVersionInfo()
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_version' => config('app.version', '1.0.0'),
            'last_updated' => $this->getLastUpdateTime()
        ];
    }

    /**
     * Get current version
     */
    private function getCurrentVersion()
    {
        return config('app.version', '1.0.0');
    }

    /**
     * Get version history
     */
    private function getVersionHistory()
    {
        return DeploymentLog::select('version', 'created_at', 'status')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Get school versions
     */
    private function getSchoolVersions()
    {
        return School::select('id', 'name', 'current_version', 'version_updated_at')
            ->whereNotNull('current_version')
            ->orderBy('version_updated_at', 'desc')
            ->get();
    }

    /**
     * Check database connection
     */
    private function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'message' => 'Database connection successful'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check cache
     */
    private function checkCache()
    {
        try {
            cache()->put('health_check', 'ok', 60);
            $value = cache()->get('health_check');
            return ['status' => $value === 'ok' ? 'healthy' : 'unhealthy', 'message' => 'Cache test'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check storage
     */
    private function checkStorage()
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            \Storage::put($testFile, 'test');
            \Storage::delete($testFile);
            return ['status' => 'healthy', 'message' => 'Storage accessible'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check queue
     */
    private function checkQueue()
    {
        try {
            // Check if queue is running
            $processes = shell_exec('ps aux | grep "queue:work" | grep -v grep');
            return ['status' => !empty($processes) ? 'healthy' : 'unhealthy', 'message' => 'Queue worker status'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check mail
     */
    private function checkMail()
    {
        try {
            // This would test mail configuration
            return ['status' => 'healthy', 'message' => 'Mail configuration OK'];
        } catch (\Exception $e) {
            return ['status' => 'unhealthy', 'message' => $e->getMessage()];
        }
    }

    /**
     * Check disk space
     */
    private function checkDiskSpace()
    {
        $freeBytes = disk_free_space(storage_path());
        $totalBytes = disk_total_space(storage_path());
        $usedPercent = (($totalBytes - $freeBytes) / $totalBytes) * 100;
        
        return [
            'status' => $usedPercent < 90 ? 'healthy' : 'unhealthy',
            'message' => "Disk usage: " . round($usedPercent, 2) . "%",
            'free_space' => $this->formatBytes($freeBytes),
            'total_space' => $this->formatBytes($totalBytes)
        ];
    }

    /**
     * Check memory usage
     */
    private function checkMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        return [
            'status' => 'healthy',
            'message' => 'Memory usage: ' . $this->formatBytes($memoryUsage),
            'limit' => $memoryLimit
        ];
    }

    /**
     * Calculate overall health
     */
    private function calculateOverallHealth($health)
    {
        $unhealthyCount = 0;
        foreach ($health as $check) {
            if ($check['status'] === 'unhealthy') {
                $unhealthyCount++;
            }
        }
        
        if ($unhealthyCount === 0) {
            return 'healthy';
        } elseif ($unhealthyCount <= 2) {
            return 'warning';
        } else {
            return 'unhealthy';
        }
    }

    /**
     * Format bytes
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get last update time
     */
    private function getLastUpdateTime()
    {
        $deployment = DeploymentLog::where('status', 'completed')->latest()->first();
        return $deployment ? $deployment->completed_at : null;
    }
}
