<?php

namespace App\Repositories\Eloquent;

use App\Models\RoomAnswer;
use App\Repositories\Contracts\AnswerRepositoryInterface;

class AnswerRepository implements AnswerRepositoryInterface
{
    public function create(array $data): RoomAnswer
    {
        return RoomAnswer::create($data);
    }

    public function getByParticipant(int $participantId)
    {
        return RoomAnswer::where('participant_id', $participantId)->get();
    }

    public function getCorrectCount(int $participantId): int
    {
        return RoomAnswer::where('participant_id', $participantId)
            ->where('is_correct', true)
            ->count();
    }
}
