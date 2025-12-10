<?php

namespace App\Services\LearningCorner;

use App\Repositories\Contracts\LearningModuleRepositoryInterface;
use App\Models\LearningModule;

class LearningModuleService
{
    public function __construct(
        protected LearningModuleRepositoryInterface $modules
    ) {}

    public function create(array $data): LearningModule
    {
        return $this->modules->create($data);
    }

    public function update(LearningModule $module, array $data): LearningModule
    {
        return $this->modules->update($module, $data);
    }

    public function delete(LearningModule $module): void
    {
        $this->modules->delete($module);
    }

    public function find(int $id): ?LearningModule
    {
        return $this->modules->findById($id);
    }

    public function getByTeacher(string $teacherId)
    {
        return $this->modules->getByTeacher($teacherId);
    }
}
