<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\CreateRoomRequest;
use App\Services\Room\RoomService;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function __construct(protected RoomService $service) {}

    public function store(CreateRoomRequest $request): JsonResponse
    {
        $user = Auth::user();
        $data = array_merge($request->validated(), ['user_id' => $user->id]);
        $room = $this->service->create($data);
        return response()->json([
            'room' => $room,
            'message' => 'Room created successfully'
        ], 201);
    }

    public function start(Room $room): JsonResponse
    {
        $user = Auth::user();
        if ($room->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $r = $this->service->start($room);
        return response()->json($r);
    }

    public function finish(Room $room): JsonResponse
    {
        $user = Auth::user();
        if ($room->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $r = $this->service->finish($room);
        return response()->json($r);
    }

    // Public: fetch questions for the room (for players)
    public function questions(Room $room): JsonResponse
    {
        $qs = $room->questionSet->materials()
            ->with(['questions.options'])
            ->get()
            ->flatMap->questions;
        return response()->json($qs);
    }
}

