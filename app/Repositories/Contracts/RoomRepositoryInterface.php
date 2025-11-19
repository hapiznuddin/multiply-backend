<?php

namespace App\Repositories\Contracts;

use App\Models\Room;

interface RoomRepositoryInterface
{
    public function create(array $data): Room;

    public function update(Room $room, array $data): Room;

    public function findByCode(string $code): ?Room;

    public function findById(int $id): ?Room;
}
