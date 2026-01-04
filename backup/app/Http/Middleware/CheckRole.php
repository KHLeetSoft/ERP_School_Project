<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check both default guard and admin guard
        $user = Auth::user();
        if (!$user) {
            $user = Auth::guard('admin')->user();
        }

        if (! $user) {
            return redirect()->route('login');
        }

        // Support both relation (role->name) and legacy string column (role)
        $roleName = optional($user->role)->name ?? $user->role;  
        $roleId   = $user->role_id ?? null;

        $allowed = false;

        foreach ($roles as $r) {
            // Numeric check: e.g. "role_id"
            if (is_numeric($r) && (int)$r === (int)$roleId) {
                $allowed = true;
                break;
            }

            // String check: case-insensitive match with normalized spaces
            $normalizedRole = str_replace(' ', '', Str::lower($roleName));
            $normalizedRequired = str_replace(' ', '', Str::lower($r));
            
            if ($normalizedRole === $normalizedRequired) {
                $allowed = true;
                break;
            }
            
            // Also check for common variations
            if (in_array($normalizedRole, ['superadmin', 'super_admin']) && in_array($normalizedRequired, ['superadmin', 'super_admin'])) {
                $allowed = true;
                break;
            }
        }

        if (! $allowed) {
            abort(403, "Unauthorized. You are “{$roleName}” but needed: " . implode(',', $roles));
        }

        return $next($request);
    }
}
