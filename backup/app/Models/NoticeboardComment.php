<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeboardComment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'noticeboard_id', 'content', 'parent_id', 'is_approved'];

    protected $casts = [
        'is_approved' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function noticeboard()
    {
        return $this->belongsTo(Noticeboard::class);
    }

    public function parent()
    {
        return $this->belongsTo(NoticeboardComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(NoticeboardComment::class, 'parent_id');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    // Methods
    public function approve()
    {
        $this->update(['is_approved' => true]);
    }

    public function reject()
    {
        $this->update(['is_approved' => false]);
    }

    public function isReply()
    {
        return !is_null($this->parent_id);
    }

    public function hasReplies()
    {
        return $this->replies()->count() > 0;
    }
}
