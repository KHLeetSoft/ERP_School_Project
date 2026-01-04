<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Substitution extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'substitute_id',
        'date',
        'school_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id'); // assuming teachers are in users table
    }

    public function substitute()
    {
        return $this->belongsTo(User::class, 'substitute_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
