<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageFolder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'user_id',
        'color',
        'icon',
        'position',
    ];

    protected $casts = [
        'position' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function folderItems()
    {
        return $this->hasMany(MessageFolderItem::class, 'folder_id');
    }

    public function messages()
    {
        return $this->belongsToMany(Message::class, 'message_folder_items', 'folder_id', 'message_id')
                    ->withPivot('user_id')
                    ->withTimestamps();
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeSystem($query)
    {
        return $query->whereIn('slug', ['inbox', 'sent', 'drafts', 'trash', 'archive', 'spam']);
    }

    public function scopeCustom($query)
    {
        return $query->whereNotIn('slug', ['inbox', 'sent', 'drafts', 'trash', 'archive', 'spam']);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }

    // Methods
    public function getMessageCountAttribute()
    {
        return $this->folderItems()->count();
    }

    public function getUnreadCountAttribute()
    {
        return $this->folderItems()
                    ->whereHas('message', function($query) {
                        $query->whereNull('read_at');
                    })
                    ->count();
    }
}