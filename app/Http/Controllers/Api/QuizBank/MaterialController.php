<?php

namespace App\Http\Controllers\Api\QuizBank;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizBank\CreateMaterialRequest;
use App\Services\QuizBank\MaterialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function __construct(protected MaterialService $service) {}

    public function index(): JsonResponse
    {
        $materials = $this->service->getByTeacher(Auth::user()->id);
        return response()->json($materials);
    }

    public function store(CreateMaterialRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = array_merge($request->validated(), ['user_id' => $user->id]);
        $material = $this->service->create($data);
        return response()->json($material, 201);
    }
    public function update(CreateMaterialRequest $request, $id): JsonResponse
    {
        $material = $this->service->find($id);
        abort_unless($material, 404);
        $this->service->update($material, $request->validated());
        return response()->json($material->fresh());
    }

    public function destroy($id): JsonResponse
    {
        $material = $this->service->find($id);
        abort_unless($material, 404);
        $this->service->delete($material);
        return response()->json(null, 204);
    }
}

