<?php

namespace App\Services\Room;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Repositories\Contracts\AnswerRepositoryInterface;
use App\Models\RoomAnswer;
use App\Models\RoomParticipant;
use Illuminate\Support\Facades\DB;

class AnswerService
{
    public function __construct(
        protected AnswerRepositoryInterface $answers
    ) {}

    public function storeAnswer(RoomParticipant $participant, Question $question, $rawAnswer): array
    {
        return DB::transaction(function () use ($participant, $question, $rawAnswer) {

            // Determine correctness
            if ($question->type === 'multiple_choice') {
                $option = QuestionOption::where('id', $rawAnswer)
                    ->where('question_id', $question->id)
                    ->first();

                $isCorrect = $option && $option->is_correct;
            } else {
                $isCorrect = strtolower(trim($question->correct_answer)) === strtolower(trim($rawAnswer));
            }

            $points = $isCorrect ? 100 : 0;

            // Save answer
            $answer = RoomAnswer::create([
                'room_id'             => $participant->room_id,
                'room_participant_id' => $participant->id,
                'question_id'         => $question->id,
                'answer'              => (string) $rawAnswer,
                'is_correct'          => $isCorrect,
                'points'              => $points,
                'answered_at'         => now(),
            ]);

            // Update participant score
            $participant->score += $points;
            $participant->save();

            // Compute rank
            $rank = RoomParticipant::where('room_id', $participant->room_id)
                ->where('score', '>', $participant->score) // higher score only
                ->count() + 1;

            return [
                'answer'      => $answer,
                'total_score' => $participant->score,
                'rank'        => $rank,
            ];
        });
    }
    public function getByParticipant(int $participantId)
    {
        return $this->answers->getByParticipant($participantId);
    }

    public function getCorrectCount(int $participantId): int
    {
        return $this->answers->getCorrectCount($participantId);
    }
}
