<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'user_id',
        'question_set_id',
        'code',
        'status',
        'starts_at',
        'started_at',
        'finished_at',
        'title',
        'max_players'
    ];

    protected $casts = [
        'starts_at'   => 'datetime',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questionSet()
    {
        return $this->belongsTo(QuestionSet::class);
    }

    public function participants()
    {
        return $this->hasMany(RoomParticipant::class);
    }

    public function answers()
    {
        return $this->hasMany(RoomAnswer::class);
    }
}
