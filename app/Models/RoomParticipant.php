<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomParticipant extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'guest_token',
        'score',
        'joined_at',
        'finished_at',
    ];

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
        return $this->hasMany(RoomAnswer::class, 'participant_id');
    }
}
