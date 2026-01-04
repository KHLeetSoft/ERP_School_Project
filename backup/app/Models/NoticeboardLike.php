<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeboardLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'noticeboard_id', 'liked_at'];

    protected $casts = [
        'liked_at' => 'datetime'
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

    // Scopes
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('liked_at', '>=', now()->subDays($days));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function toggle()
    {
        $this->delete();
        return false;
    }

    public function markAsLiked()
    {
        $this->update(['liked_at' => now()]);
    }
}
