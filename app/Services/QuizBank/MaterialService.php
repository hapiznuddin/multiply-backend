<?php

namespace App\Services\QuizBank;

use App\Repositories\Contracts\MaterialRepositoryInterface;
use App\Models\Material;

class MaterialService
{
    public function __construct(
        protected MaterialRepositoryInterface $materials
    ) {}

    public function create(array $data): Material
    {
        return $this->materials->create($data);
    }

    public function update(Material $material, array $data): Material
    {
        return $this->materials->update($material, $data);
    }

    public function delete(Material $material): void
    {
        $this->materials->delete($material);
    }

    public function find(int $id): ?Material
    {
        return $this->materials->findById($id);
    }

    public function getByTeacher(string $teacherId)
    {
        return $this->materials->getByTeacher($teacherId);
    }
}
