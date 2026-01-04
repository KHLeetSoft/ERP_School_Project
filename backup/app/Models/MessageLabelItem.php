<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageLabelItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'label_id',
    ];

    // Relationships
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function label()
    {
        return $this->belongsTo(MessageLabel::class, 'label_id');
    }

    // Scopes
    public function scopeByMessage($query, $messageId)
    {
        return $query->where('message_id', $messageId);
    }

    public function scopeByLabel($query, $labelId)
    {
        return $query->where('label_id', $labelId);
    }
}
