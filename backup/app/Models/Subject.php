<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_section_id',
        'subject_name',
        'subject_code',
        'type',
        'status',
    ];

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class, 'class_section_id');
    }

    /**
     * Virtual accessor to make `$subject->name` available, mapped to `subject_name`.
     */
    public function getNameAttribute()
    {
        return $this->subject_name;
    }
}
