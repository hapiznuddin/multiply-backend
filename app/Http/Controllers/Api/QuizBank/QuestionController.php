<?php

namespace App\Http\Controllers\Api\QuizBank;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizBank\CreateQuestionRequest;
use App\Services\QuizBank\QuestionService;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function __construct(protected QuestionService $service) {}

    public function store(CreateQuestionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $question = $this->service->create($data);
        return response()->json($question->load('options'), 201);
    }

    public function show($id): JsonResponse
    {
        $q = $this->service->find($id);
        abort_unless($q, 404);
        return response()->json($q->load('options'));
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
        return response()->json(null, 204);
    }
}