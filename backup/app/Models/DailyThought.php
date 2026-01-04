<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyThought extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'thought_en',
        'thought_hi',
        'source',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}


