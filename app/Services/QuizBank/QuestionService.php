<?php

namespace App\Services\QuizBank;

use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Models\Question;

class QuestionService
{
    public function __construct(
        protected QuestionRepositoryInterface $questions
    ) {}

    public function create(array $data): Question
    {
        return $this->questions->create($data);
    }

    public function update(Question $question, array $data): Question
    {
        return $this->questions->update($question, $data);
    }

    public function delete(Question $question): void
    {
        $this->questions->delete($question);
    }

    public function find(int $id): ?Question
    {
        return $this->questions->findById($id);
    }

    public function getByMaterial(int $materialId)
    {
        return $this->questions->getByMaterial($materialId);
    }
}
