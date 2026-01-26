<?php

namespace App\Repositories\Contracts;

use App\Models\Material;

interface MaterialRepositoryInterface
{
    public function create(array $data): Material;

    public function update(Material $material, array $data): Material;

    public function delete(Material $material): void;

    public function findById(int $id): ?Material;

    public function getByTeacher(string $teacherId);
}
