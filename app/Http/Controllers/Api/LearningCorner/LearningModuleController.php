<?php

namespace App\Http\Controllers\Api\LearningCorner;

use App\Http\Controllers\Controller;
use App\Services\LearningCorner\LearningModuleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LearningModuleController extends Controller
{
    public function __construct(
        protected LearningModuleService $service
    ) {}

    public function index(Request $request): JsonResponse
    {
        $teacherId = $request->user()->id;
        $modules = $this->service->getByTeacher($teacherId);
        return response()->json($modules);
    }

    public function show(int $id): JsonResponse
    {
        $module = $this->service->find($id);
        
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        return response()->json($module);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_url' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $validated['user_id'] = $request->user()->id;
        
        $module = $this->service->create($validated);
        return response()->json($module, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $module = $this->service->find($id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'video_url' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $updatedModule = $this->service->update($module, $validated);
        return response()->json($updatedModule);
    }

    public function destroy(int $id): JsonResponse
    {
        $module = $this->service->find($id);

        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }

        $this->service->delete($module);
        return response()->json(['message' => 'Module deleted successfully']);
    }
}
