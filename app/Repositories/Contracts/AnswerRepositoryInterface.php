<?php

namespace App\Repositories\Contracts;

use App\Models\RoomAnswer;

interface AnswerRepositoryInterface
{
    public function create(array $data): RoomAnswer;

    public function getByParticipant(int $participantId);

    public function getCorrectCount(int $participantId): int;
}
