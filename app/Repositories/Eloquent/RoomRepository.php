<?php

namespace App\Repositories\Eloquent;

use App\Models\Room;
use App\Repositories\Contracts\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    public function create(array $data): Room
    {
        return Room::create($data);
    }

    public function update(Room $room, array $data): Room
    {
        $room->update($data);
        return $room;
    }

    public function findByCode(string $code): ?Room
    {
        return Room::where('code', $code)->first();
    }

    public function findById(int $id): ?Room
    {
        return Room::find($id);
    }
}
