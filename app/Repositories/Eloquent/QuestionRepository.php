<?php

namespace App\Repositories\Eloquent;

use App\Models\Question;
use App\Repositories\Contracts\QuestionRepositoryInterface;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function create(array $data): Question
    {
        return Question::create($data);
    }

    public function update(Question $question, array $data): Question
    {
        $question->update($data);
        return $question;
    }

    public function delete(Question $question): void
    {
        $question->delete();
    }

    public function findById(int $id): ?Question
    {
        return Question::find($id);
    }

    public function getByMaterial(int $materialId)
    {
        return Question::where('material_id', $materialId)->get();
    }
}
