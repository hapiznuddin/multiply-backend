<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\JoinRoomRequest;
use App\Services\Room\ParticipantService;
use App\Repositories\Contracts\RoomRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ParticipantController extends Controller
{
    public function __construct(
        protected ParticipantService $participants,
        protected RoomRepositoryInterface $rooms
    ) {}

    public function join(JoinRoomRequest $request): JsonResponse
    {
        $r = $this->rooms->findByCode($request->input('code'));
        if (! $r) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        // allow join only if waiting or running
        if (! in_array($r->status, ['waiting','running'])) {
            return response()->json(['message' => 'Room not accepting participants'], 422);
        }

        $participant = $this->participants->create([
            'room_id' => $r->id,
            'name' => $request->input('name'),
            'guest_token' => \Illuminate\Support\Str::uuid(),
            'joined_at' => now(),
        ]);

        return response()->json([
            'participant' => $participant,
            'room' => $r,
            'guest_token' => $participant->guest_token,
        ], 201);
    }
}
