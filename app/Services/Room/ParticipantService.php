<?php

namespace App\Services\Room;

use App\Repositories\Contracts\ParticipantRepositoryInterface;
use App\Models\RoomParticipant;

class ParticipantService
{
    public function __construct(
        protected ParticipantRepositoryInterface $participants
    ) {}

    public function create(array $data): RoomParticipant
    {
        return $this->participants->create($data);
    }

    public function findByToken(string $token): ?RoomParticipant
    {
        return $this->participants->findByToken($token);
    }

    public function find(int $id): ?RoomParticipant
    {
        return $this->participants->findById($id);
    }

    public function getByRoom(int $roomId)
    {
        return $this->participants->getByRoom($roomId);
    }
}
