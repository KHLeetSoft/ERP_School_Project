<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostelRoom extends Model
{
    use HasFactory;

    protected $table = 'hostel_rooms';

    protected $fillable = [
        'school_id',
        'hostel_id',
        'room_no',
        'type',
        'capacity',
        'gender',
        'floor',
        'status',
        'notes',
    ];

    public function hostel(): BelongsTo
    {
        return $this->belongsTo(Hostel::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(StudentHostel::class, 'room_id');
    }
}


