<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'school_id', 'visitor_name', 'purpose', 'phone',
        'date', 'in_time', 'out_time', 'note'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function school()
    {
        return $this->belongsTo(\App\Models\School::class, 'school_id');
    }
} 