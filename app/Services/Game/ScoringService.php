<?php

namespace App\Services\Game;

use App\Models\RoomParticipant;
use App\Services\Room\AnswerService;

class ScoringService
{
    public function __construct(
        protected AnswerService $answers
    ) {}

    public function calculate(RoomParticipant $participant): int
    {
        $correct = $this->answers->getCorrectCount($participant->id);
        $score = $correct * 10;

        $participant->update([
            'score'       => $score,
            'finished_at' => now(),
        ]);

        return $score;
    }

    public function calculateAll(int $roomId)
    {
        $participants = RoomParticipant::where('room_id', $roomId)->get();

        foreach ($participants as $p) {
            $this->calculate($p);
        }
    }
}
