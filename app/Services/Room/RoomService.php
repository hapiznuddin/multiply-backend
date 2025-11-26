<?php

namespace App\Services\Room;

use App\Repositories\Contracts\RoomRepositoryInterface;
use App\Models\Room;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class RoomService
{
    public function __construct(
        protected RoomRepositoryInterface $rooms
    ) {}

    
    public function create(array $data, string $userId): Room
    {
        $data['user_id'] = $userId;
        $data['code'] = $this->generateCode();
        $data['status'] = 'created';
        
        return $this->rooms->create($data);
    }
    
    public function generateCode(): string
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Room::where('code', $code)->exists());

        return $code;
    }

    public function update(Room $room, array $data): Room
    {
        return $this->rooms->update($room, $data);
    }

    public function find(int $id): ?Room
    {
        return $this->rooms->findById($id);
    }

    public function findByCode(string $code): ?Room
    {
        return $this->rooms->findByCode($code);
    }

    public function listForUser(string $userId)
    {
        return Room::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public function start(Room $room): Room
    {
        return $this->rooms->update($room, [
            'status' => 'started',
            'started_at' => now()
        ]);
    }

    public function finish(Room $room): Room
    {
        return $this->rooms->update($room, [
            'status' => 'finished',
            'finished_at' => now()
        ]);
    }

    public function getQuestions(Room $room)
    {
        return $room->material
        ->questions()
        ->with(['options' => function ($q) {
            $q->select('id', 'question_id', 'option_text');
        }])
        ->get();
    }
}
