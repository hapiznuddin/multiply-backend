<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionSetMaterial extends Model
{
    protected $table = 'question_set_materials';

    protected $fillable = [
        'question_set_id',
        'material_id',
    ];
}
