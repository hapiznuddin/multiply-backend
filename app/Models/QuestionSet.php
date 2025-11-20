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

public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}

public function materials()
{
    return $this->belongsToMany(
        QuestionSet::class,
        'question_set_materials', // <-- nama pivot
        'material_id',
        'question_set_id'
    );
}

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function questions()
{
    return $this->hasManyThrough(
        Question::class,
        Material::class,
        'id',            // primary key of materials
        'material_id',   // FK di questions
        'id',            // pk di question_sets
        'id'             // fk di pivot material_question_set (but not used)
    );
}
}
