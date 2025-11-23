<?php

namespace App\Repositories\Eloquent;

use App\Models\Material;
use App\Repositories\Contracts\MaterialRepositoryInterface;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function create(array $data): Material
    {
        return Material::create($data);
    }

    public function update(Material $material, array $data): Material
    {
        $material->update($data);
        return $material;
    }

    public function delete(Material $material): void
    {
        $material->delete();
    }

    public function findById(int $id): ?Material
    {
        return Material::find($id);
    }

    public function getByTeacher(string $teacherId)
    {
        return Material::where('user_id', $teacherId)
        ->withCount('questions')
        ->get();
    }
}
