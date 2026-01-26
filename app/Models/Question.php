<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'material_id',
        'question_text',
        'type',
        'correct_answer',
    ];

public function material()
{
    return $this->belongsTo(\App\Models\Material::class, 'material_id');
}

public function options()
{
    return $this->hasMany(\App\Models\QuestionOption::class, 'question_id');
}
}
