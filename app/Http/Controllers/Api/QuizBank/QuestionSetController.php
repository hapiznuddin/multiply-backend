<?php

namespace App\Http\Controllers\Api\QuizBank;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizBank\CreateQuestionSetRequest;
use App\Services\QuizBank\QuestionSetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class QuestionSetController extends Controller
{
    public function __construct(protected QuestionSetService $service) {}

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $sets = $user->questionSets->with('materials')->get();
        return response()->json($sets);
    }

    public function store(CreateQuestionSetRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = array_merge($request->validated(), ['user_id' => $user->id]);
        $set = $this->service->create($data);
        return response()->json([
            'question_set' => $set->load('materials'),
            'message' => 'Question set created successfully'
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $s = $this->service->find($id);
        abort_unless($s, 404);
        return response()->json($s->load('materials'));
    }

    public function update(CreateQuestionSetRequest $request, $id): JsonResponse
    {
        $s = $this->service->find($id);
        abort_unless($s, 404);
        $updated = $this->service->update($s, $request->validated());
        return response()->json($updated->load('materials'));
    }

    public function destroy($id): JsonResponse
    {
        $s = $this->service->find($id);
        abort_unless($s, 404);
        $s->delete();
        return response()->json([
            'message' => 'Question set deleted successfully',
        ], 204);
    }
}