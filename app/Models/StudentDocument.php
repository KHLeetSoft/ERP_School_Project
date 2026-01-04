<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentDocument extends Model
{
    use HasFactory;

    protected $table = 'students_documents';

    protected $fillable = [
        'school_id',
        'student_id',
        'document_type',
        'title',
        'original_name',
        'file_path',
        'mime_type',
        'file_size',
        'issued_date',
        'expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }
}


