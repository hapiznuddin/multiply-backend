<?php

namespace App\Repositories\Contracts;

use App\Models\RoomParticipant;

interface ParticipantRepositoryInterface
{
    public function create(array $data): RoomParticipant;

    public function findByToken(string $token): ?RoomParticipant;

    public function findById(int $id): ?RoomParticipant;

    public function getByRoom(int $roomId);
}
