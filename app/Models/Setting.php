<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'category',
        'type',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null, $category = null)
    {
        $cacheKey = $category ? "setting.{$category}.{$key}" : "setting.{$key}";
        
        return Cache::remember($cacheKey, 3600, function () use ($key, $default, $category) {
            $query = static::where('key', $key);
            
            if ($category) {
                $query->where('category', $category);
            }
            
            $setting = $query->first();
            
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $category = 'general', $type = 'string', $description = null, $isPublic = false)
    {
        $setting = static::updateOrCreate(
            ['key' => $key, 'category' => $category],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
                'is_public' => $isPublic
            ]
        );

        // Clear cache
        $cacheKey = "setting.{$category}.{$key}";
        Cache::forget($cacheKey);
        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory($category)
    {
        return Cache::remember("settings.{$category}", 3600, function () use ($category) {
            return static::where('category', $category)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Get all public settings
     */
    public static function getPublic()
    {
        return Cache::remember('settings.public', 3600, function () {
            return static::where('is_public', true)
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache()
    {
        Cache::flush();
    }

    /**
     * Get settings with their types for form rendering
     */
    public static function getFormSettings($category)
    {
        return static::where('category', $category)
            ->orderBy('key')
            ->get()
            ->mapWithKeys(function ($setting) {
                return [
                    $setting->key => [
                        'value' => $setting->value,
                        'type' => $setting->type,
                        'description' => $setting->description,
                        'is_public' => $setting->is_public
                    ]
                ];
            });
    }

    /**
     * Boot method to clear cache when model is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            $setting->clearCache();
        });

        static::deleted(function ($setting) {
            $setting->clearCache();
        });
    }
}
