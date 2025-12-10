<?php

namespace App\Repositories\Contracts;

use App\Models\LearningModule;

interface LearningModuleRepositoryInterface
{
    public function create(array $data): LearningModule;

    public function update(LearningModule $module, array $data): LearningModule;

    public function delete(LearningModule $module): void;

    public function findById(int $id): ?LearningModule;

    public function getByTeacher(string $teacherId);
}
