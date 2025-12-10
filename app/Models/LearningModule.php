<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningModule extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'video_url',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
