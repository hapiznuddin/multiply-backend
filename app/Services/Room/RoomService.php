<?php

namespace App\Services\Room;

use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Models\Room;

class RoomService
{
    public function __construct(
        protected RoomRepositoryInterface $rooms
    ) {}

    public function create(array $data): Room
    {
        return $this->rooms->create($data);
    }

    public function update(Room $room, array $data): Room
    {
        return $this->rooms->update($room, $data);
    }

    public function findByCode(string $code): ?Room
    {
        return $this->rooms->findByCode($code);
    }

    public function find(int $id): ?Room
    {
        return $this->rooms->findById($id);
    }

    public function start(Room $room): Room
    {
        return $this->rooms->update($room, [
            'status'    => 'running',
            'starts_at' => now()
        ]);
    }

    public function finish(Room $room): Room
    {
        return $this->rooms->update($room, [
            'status'      => 'finished',
            'finished_at' => now()
        ]);
    }
}
