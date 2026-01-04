<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RfidAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
        'card_uid',
        'timestamp',
        'direction',
        'device_name',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


