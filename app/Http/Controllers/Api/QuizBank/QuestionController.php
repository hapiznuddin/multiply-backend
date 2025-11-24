<?php

namespace App\Http\Controllers\Api\QuizBank;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizBank\CreateQuestionRequest;
use App\Models\Material;
use App\Services\QuizBank\QuestionService;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function __construct(protected QuestionService $service) {}

    public function store(CreateQuestionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $question = $this->service->create($data);
        return response()->json([
            'question' => $question->load('options'), 
            'message' => 'Question created successfully'
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $q = $this->service->find($id);
        abort_unless($q, 404);
        return response()->json($q->load('options'));
    }

    public function byMaterial(Material $material): JsonResponse
    {
        $material->load(['questions.options']);

        return response()->json([
            'material'  => $material->withCount('questions')->first(),
            'questions' => $material->questions
        ]);
    }

    public function byQuestionByMaterial(Material $material): JsonResponse
    {
        $questions = $material->questions()->with('options')->get();

        return response()->json($questions);
    }


    public function multipleChoice(Material $material)
    {
        return response()->json(
            $this->service->getMultipleChoiceByMaterial($material)
        );
    }

    public function input(Material $material)
    {
        return response()->json(
            $this->service->getInputByMaterial($material)
        );
    }

    public function update(CreateQuestionRequest $request, $id): JsonResponse
    {
        $q = $this->service->find($id);
        abort_unless($q, 404);
        $this->service->update($q, $request->validated());
        return response()->json($q->fresh()->load('options'));
    }

    public function destroy($id): JsonResponse
    {
        $q = $this->service->find($id);
        abort_unless($q, 404);
        $this->service->delete($q);
        return response()->json([
            'message' => 'Question deleted successfully',
        ], 204);
    }
}