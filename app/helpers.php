

<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('app_setting')) {
    /**
     * Get application setting from the database.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    if (!function_exists('app_setting')) {
        function app_setting($key, $default = null) {
            return \Illuminate\Support\Facades\DB::table('settings')->where('key', $key)->value('value') ?? $default;
        }
    }
}


