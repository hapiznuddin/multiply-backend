<?php

namespace App\Services\Room;

use App\Repositories\Contracts\AnswerRepositoryInterface;
use App\Models\RoomAnswer;

class AnswerService
{
    public function __construct(
        protected AnswerRepositoryInterface $answers
    ) {}

    public function create(array $data): RoomAnswer
    {
        return $this->answers->create($data);
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
