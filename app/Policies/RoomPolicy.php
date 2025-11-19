<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function update(User $user, Room $room): bool
    {
        // hanya teacher yang membuat room boleh start/finish
        return $room->teacher_id === $user->id;
    }

    // kamu bisa tambahkan method lain:
    public function start(User $user, Room $room): bool
    {
        return $room->teacher_id === $user->id;
    }

    public function finish(User $user, Room $room): bool
    {
        return $room->teacher_id === $user->id;
    }
}

