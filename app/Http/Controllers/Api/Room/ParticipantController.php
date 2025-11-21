<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\JoinRoomRequest;
use App\Models\Room;
use App\Models\RoomParticipant;
use App\Services\Room\ParticipantService;
use App\Repositories\Contracts\RoomRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    public function __construct(
        protected ParticipantService $participants,
        protected RoomRepositoryInterface $rooms
    ) {}

    public function join(JoinRoomRequest $request): JsonResponse
    {
    $room = Room::where('code', $request->room_code)->firstOrFail();

    if (!in_array($room->status, ['active', 'started'])) {
        return response()->json(['message' => 'Room not accepting participants'], 403);
    }

    $participant = RoomParticipant::create([
        'room_id'          => $room->id,
        'participant_name' => $request->participant_name,
        'guest_token'      => Str::uuid(),
        'joined_at'        => now(),
    ]);

    return response()->json([
        'message' => 'Joined successfully',
        'participant' => $participant
    ], 201);
    }
}
