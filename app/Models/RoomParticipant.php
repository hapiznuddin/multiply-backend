<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomParticipant extends Model
{
    protected $guarded = [];

    protected $casts = [
        'joined_at'   => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function answers()
    {
        return $this->hasMany(RoomAnswer::class, 'room_participant_id');
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
