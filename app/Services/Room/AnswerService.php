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

    public function storeAnswer(RoomParticipant $participant, Question $question, $rawAnswer, $timeTaken = null): array
    {
        return DB::transaction(function () use ($participant, $question, $rawAnswer, $timeTaken) {

            // Determine correctness
            if ($question->type === 'multiple_choice') {
                $option = QuestionOption::where('id', $rawAnswer)
                    ->where('question_id', $question->id)
                    ->first();

                $isCorrect = $option && $option->is_correct;
            } else {
                $isCorrect = strtolower(trim($question->correct_answer)) === strtolower(trim($rawAnswer));
            }

            $basePoints = $isCorrect ? 100 : 0;
            $speedBonus = 0;
            $streakMultiplier = 1.0;

            if ($isCorrect) {
                // Update streak
                $participant->current_streak += 1;
                if ($participant->current_streak > $participant->max_streak) {
                    $participant->max_streak = $participant->current_streak;
                }

                // Calculate speed bonus (if timer is active in room)
                $room = $participant->room;
                if ($timeTaken !== null && $room->time_limit_per_question > 0) {
                    // Max bonus is 50 points if answered in 0 seconds, 0 bonus if answered at time limit
                    $speedBonus = max(0, round((( $room->time_limit_per_question - $timeTaken ) / $room->time_limit_per_question) * 50));
                }

                // Streak multiplier: +10% per streak (max 2.0x / 10 streak)
                $streakMultiplier = min(2.0, 1.0 + (($participant->current_streak - 1) * 0.1));
            } else {
                // Reset streak
                $participant->current_streak = 0;
            }

            $finalPoints = round(($basePoints + $speedBonus) * $streakMultiplier);
            $participant->total_time += ($timeTaken ?? 0);

            // Save answer
            $answer = RoomAnswer::create([
                'room_id'             => $participant->room_id,
                'room_participant_id' => $participant->id,
                'question_id'         => $question->id,
                'answer'              => (string) $rawAnswer,
                'is_correct'          => $isCorrect,
                'points'              => $finalPoints,
                'speed_bonus'         => $speedBonus,
                'time_taken'          => $timeTaken,
                'answered_at'         => now(),
            ]);

            // Update participant score
            $participant->score += $finalPoints;
            $participant->save();

            // Achievement Checks
            $newAchievements = $this->checkAchievements($participant, $isCorrect, $timeTaken, $finalPoints);

            // Compute rank
            $rank = RoomParticipant::where('room_id', $participant->room_id)
                ->where('score', '>', $participant->score) // higher score only
                ->count() + 1;

            return [
                'answer'           => $answer,
                'total_score'      => $participant->score,
                'rank'             => $rank,
                'current_streak'   => $participant->current_streak,
                'speed_bonus'      => $speedBonus,
                'new_achievements' => $newAchievements,
            ];
        });
    }

    protected function checkAchievements(RoomParticipant $participant, bool $isCorrect, $timeTaken, int $points): array
    {
        $newlyUnlocked = [];
        $achievements = \App\Models\Achievement::all();

        foreach ($achievements as $achievement) {
            // Check if already unlocked
            if (\App\Models\UserAchievement::where('room_participant_id', $participant->id)
                ->where('achievement_id', $achievement->id)
                ->exists()) {
                continue;
            }

            $unlocked = false;

            switch ($achievement->requirement_type) {
                case 'participation':
                    // e.g. First Blood
                    if ($isCorrect && $achievement->name === 'First Blood') {
                        $unlocked = true;
                    }
                    break;
                case 'streak':
                    if ($participant->current_streak >= $achievement->requirement_value) {
                        $unlocked = true;
                    }
                    break;
                case 'speed':
                    if ($isCorrect && $timeTaken !== null && $timeTaken <= $achievement->requirement_value) {
                        $unlocked = true;
                    }
                    break;
                case 'score':
                    if ($participant->score >= $achievement->requirement_value) {
                        $unlocked = true;
                    }
                    break;
                case 'accuracy':
                    // Accuracy check typically after room finish, but let's see if we want it here
                    // If requirement_value is 100 (Perfect Score), check if all answers so far are correct
                    // This might be better at the end of the room.
                    break;
            }

            if ($unlocked) {
                \App\Models\UserAchievement::create([
                    'room_participant_id' => $participant->id,
                    'achievement_id'      => $achievement->id,
                    'unlocked_at'         => now(),
                ]);
                $newlyUnlocked[] = $achievement;
            }
        }

        return $newlyUnlocked;
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
