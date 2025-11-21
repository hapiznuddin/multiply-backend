<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAnswer extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_correct'  => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function participant()
    {
        return $this->belongsTo(RoomParticipant::class, 'participant_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
