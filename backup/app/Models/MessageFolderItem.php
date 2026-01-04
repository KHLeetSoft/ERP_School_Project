<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageFolderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'folder_id',
        'user_id',
    ];

    // Relationships
    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function folder()
    {
        return $this->belongsTo(MessageFolder::class, 'folder_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByFolder($query, $folderId)
    {
        return $query->where('folder_id', $folderId);
    }

    public function scopeByMessage($query, $messageId)
    {
        return $query->where('message_id', $messageId);
    }
}
