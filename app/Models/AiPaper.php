<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPaper extends Model
{
    use HasFactory;

    protected $table = 'ai_papers';

    protected $fillable = [
        'school_id',
        'created_by',
        'subject',
        'topic',
        'type',
        'difficulty',
        'num_questions',
        'payload',
        'pdf_path',
        'doc_path',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}


