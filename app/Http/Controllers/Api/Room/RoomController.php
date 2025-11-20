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
    public function __construct(
        protected RoomService $service
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(
            $this->service->listForUser(Auth::id())
        );
    }

    public function store(CreateRoomRequest $request): JsonResponse
    {
        $room = $this->service->create(
            $request->validated(),
            Auth::id()
        );

        return response()->json([
            'room' => $room,
            'message' => 'Room created successfully'
        ], 201);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json(
            $room->load('questionSet.materials')
        );
    }


    public function start(Room $room): JsonResponse
    {
        if ($room->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(
            $this->service->start($room)
        );
    }

    public function finish(Room $room): JsonResponse
    {
        if ($room->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(
            $this->service->finish($room)
        );
    }

    public function questions(Room $room): JsonResponse
    {
        return response()->json([
        'room_code' => $room->code,
        'questions' => $this->service->getQuestions($room)
    ]);
    }
}

