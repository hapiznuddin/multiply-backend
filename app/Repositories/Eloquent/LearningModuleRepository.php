<?php

namespace App\Repositories\Eloquent;

use App\Models\LearningModule;
use App\Repositories\Contracts\LearningModuleRepositoryInterface;

class LearningModuleRepository implements LearningModuleRepositoryInterface
{
    public function create(array $data): LearningModule
    {
        return LearningModule::create($data);
    }

    public function update(LearningModule $module, array $data): LearningModule
    {
        $module->update($data);
        return $module;
    }

    public function delete(LearningModule $module): void
    {
        $module->delete();
    }

    public function findById(int $id): ?LearningModule
    {
        return LearningModule::with('user')->find($id);
    }

    public function getAll()
    {
        return LearningModule::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByTeacher(string $teacherId)
    {
        return LearningModule::with('user')
            ->where('user_id', $teacherId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
