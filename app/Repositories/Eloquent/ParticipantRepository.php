<?php

namespace App\Repositories\Eloquent;

use App\Models\RoomParticipant;
use App\Repositories\Contracts\ParticipantRepositoryInterface;

class ParticipantRepository implements ParticipantRepositoryInterface
{
    public function create(array $data): RoomParticipant
    {
        return RoomParticipant::create($data);
    }

    public function findByToken(string $token): ?RoomParticipant
    {
        return RoomParticipant::where('guest_token', $token)->first();
    }

    public function findById(int $id): ?RoomParticipant
    {
        return RoomParticipant::find($id);
    }

    public function getByRoom(int $roomId)
    {
        return RoomParticipant::where('room_id', $roomId)->get();
    }
}
