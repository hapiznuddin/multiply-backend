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
use Illuminate\Support\Facades\Auth;

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
        'score'            => 0,
        'joined_at'        => now(),
    ]);

    return response()->json([
        'message' => 'Joined successfully',
        'participant' => $participant
    ], 201);
    }

    public function exit(RoomParticipant $participant): JsonResponse
    {
        // Delete the participant record
        $participant->delete();

        return response()->json([
            'message' => 'Successfully exited the room'
        ], 200);
    }

    public function getCount(): JsonResponse
    {
        $count = RoomParticipant::whereHas('room', function ($query) {
            $query->where('user_id', Auth::id());
        })->count();

        return response()->json($count);
    }
}
