<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeploymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'version',
        'description',
        'environment',
        'status',
        'started_by',
        'started_at',
        'completed_at',
        'output',
        'error_message'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function startedBy()
    {
        return $this->belongsTo(User::class, 'started_by');
    }
}
