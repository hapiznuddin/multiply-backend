<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'question_set_materials');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
