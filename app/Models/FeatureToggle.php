<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_enabled',
        'target_schools',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'target_schools' => 'array',
        'is_enabled' => 'boolean'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function schools()
    {
        return School::whereIn('id', $this->target_schools ?? []);
    }
}
