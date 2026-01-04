<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResourceBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resource_type',
        'resource_id',
        'resource_name',
        'title',
        'description',
        'booked_by',
        'school_id',
        'start_time',
        'end_time',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'approved_at' => 'datetime',
    ];
}


