<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\SubmitAnswerRequest;
use App\Models\Question;
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
        $participant = $this->participants->findById($request->room_participant_id);

        if (!$participant || $participant->guest_token !== $request->guest_token) {
            return response()->json(['message' => 'Invalid participant or token'], 403);
        }

        $room = $participant->room;

        if ($room->status !== 'started') {
            return response()->json(['message' => 'Room is not started'], 403);
        }

        $question = Question::findOrFail($request->question_id);

        // Validate question belongs to room's material
        if (!$room->material) {
            return response()->json(['message' => 'Room has no material assigned'], 403);
        }

        if ($question->material_id !== $room->material_id) {
            return response()->json(['message' => 'This question does not belong to this room'], 403);
        }

        // Process
        $result = $this->answers->storeAnswer(
            participant: $participant,
            question: $question,
            rawAnswer: $request->answer
        );

        // Get correct answer for multiple choice
        $correctAnswerId = null;
        if ($question->type === 'multiple_choice') {
            $correctOption = $question->options()->where('is_correct', true)->first();
            $correctAnswerId = $correctOption ? $correctOption->id : null;
        }

        return response()->json([
            'message'           => 'Answer recorded',
            'answer'            => $result['answer'],
            'total_score'       => $result['total_score'],
            'current_rank'      => $result['rank'],
            'correct_answer_id' => $correctAnswerId,
        ], 201);
    }
}

