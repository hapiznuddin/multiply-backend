<?php

namespace App\Repositories\Eloquent;

use App\Models\QuestionSet;
use App\Repositories\Contracts\QuestionSetRepositoryInterface;

class QuestionSetRepository implements QuestionSetRepositoryInterface
{
    public function create(array $data): QuestionSet
    {
        return QuestionSet::create($data);
    }

    public function update(QuestionSet $set, array $data): QuestionSet
    {
        $set->update($data);
        return $set;
    }

    public function findById(int $id): ?QuestionSet
    {
        return QuestionSet::find($id);
    }
}
