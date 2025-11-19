<?php

namespace App\Repositories\Contracts;

use App\Models\QuestionSet;

interface QuestionSetRepositoryInterface
{
    public function create(array $data): QuestionSet;

    public function update(QuestionSet $set, array $data): QuestionSet;

    public function findById(int $id): ?QuestionSet;
}
