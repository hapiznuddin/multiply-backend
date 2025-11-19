<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\SubmitAnswerRequest;
use App\Services\Room\AnswerService;
use App\Repositories\Contracts\ParticipantRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    public function __construct(
        protected AnswerService $answers,
        protected ParticipantRepositoryInterface $participants
    ) {}

    public function submit(SubmitAnswerRequest $request): JsonResponse
    {
        $p = $this->participants->findById($request->participant_id);
        if (! $p || $p->guest_token !== $request->guest_token) {
            return response()->json(['message' => 'Invalid participant or token'], 403);
        }

        $payload = $request->validated();

        $answer = $this->answers->create([
            'room_id' => $p->room_id,
            'participant_id' => $p->id,
            'question_id' => $payload['question_id'],
            'answer' => $payload['answer'],
            // is_correct handled inside AnswerService -> repository or scoring pipeline
        ]);

        return response()->json($answer, 201);
    }
}
