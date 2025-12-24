<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $fillable = [
        'room_participant_id',
        'achievement_id',
        'unlocked_at',
    ];

    public function participant()
    {
        return $this->belongsTo(RoomParticipant::class, 'room_participant_id');
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }
}
