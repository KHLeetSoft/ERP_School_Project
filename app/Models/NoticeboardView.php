<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeboardView extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'noticeboard_id', 'viewed_at', 'ip_address', 'user_agent'];

    protected $casts = [
        'viewed_at' => 'datetime'
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
        return $query->where('viewed_at', '>=', now()->subDays($days));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function markAsViewed()
    {
        $this->update(['viewed_at' => now()]);
    }
}
