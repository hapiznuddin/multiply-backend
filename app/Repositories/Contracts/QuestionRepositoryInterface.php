<?php

namespace App\Repositories\Contracts;

use App\Models\Question;

interface QuestionRepositoryInterface
{
    public function create(array $data): Question;

    public function update(Question $question, array $data): Question;

    public function delete(Question $question): void;

    public function findById(int $id): ?Question;

    public function getByMaterial(int $materialId);
}
