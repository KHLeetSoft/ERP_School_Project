<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    use HasFactory;

    protected $table = 'students_fees';

    protected $fillable = [
        'student_id',
        'class_id',
        'amount',
        'fee_date',
        'payment_mode',
        'transaction_id',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee_date' => 'date',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Accessor for status (since the table doesn't have a status column)
    public function getStatusAttribute()
    {
        return 'pending'; // Default status for now
    }
}
