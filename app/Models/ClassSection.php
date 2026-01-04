<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolClass;
use App\Models\Section;
class ClassSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'class_id',
        'section_id',
        'status',
        'created_by',
    ];
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
} 