<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function questions()
{
    return $this->hasMany(\App\Models\Question::class, 'material_id');
}

public function questionSets()
{
    return $this->belongsToMany(
        Material::class,
        'question_set_materials', // <-- nama pivot table kamu
        'question_set_id',       // FK di pivot ke question_sets
        'material_id'            // FK di pivot ke materials
    );
}
}
