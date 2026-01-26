<?php

namespace App\Services\QuizBank;

use App\Repositories\Contracts\QuestionSetRepositoryInterface;
use App\Models\QuestionSet;

class QuestionSetService
{
    public function __construct(
        protected QuestionSetRepositoryInterface $sets
    ) {}

    public function create(array $data): QuestionSet
    {
        $set = $this->sets->create($data);

        if (isset($data['materials'])) {
            $set->materials()->sync($data['materials']);
        }

        return $set;
    }

    public function update(QuestionSet $set, array $data): QuestionSet
    {
        $updated = $this->sets->update($set, $data);

        if (isset($data['materials'])) {
            $updated->materials()->sync($data['materials']);
        }

        return $updated;
    }

    public function find(int $id): ?QuestionSet
    {
        return $this->sets->findById($id);
    }
}
