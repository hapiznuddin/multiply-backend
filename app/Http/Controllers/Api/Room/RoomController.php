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
    // Only owner can start
    if ($room->user_id !== Auth::id()) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    // Room must be active before starting
    // if ($room->status !== 'active') {
    //     return response()->json([
    //         'message' => 'Room must be active before starting'
    //     ], 422);
    // }

    $updatedRoom = $this->service->start($room);

    return response()->json([
        'message' => 'Room started',
        'room'    => $updatedRoom
    ]);
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
    $questions = $room->questionSet
        ->materials()
        ->with([
            'questions' => function ($q) {
                $q->select(
                    'questions.id',
                    'questions.material_id',
                    'questions.question_text',
                    'questions.type'
                );
            },
            'questions.options' => function ($o) {
                $o->select(
                    'question_options.id',
                    'question_options.question_id',
                    'question_options.option_text'
                );
            }
        ])
        ->get()
        ->flatMap->questions
        ->values()
        ->map(function ($q, $index) {
            return [
                'id' => $q->id,
                'index' => $index,
                'question_text' => $q->question_text,
                'type' => $q->type,
                'options' => $q->options->map(fn($op) => [
                    'id' => $op->id,
                    'option_text' => $op->option_text
                ])
            ];
        });

    return response()->json([
        'room_id' => $room->id,
        'questions' => $questions
    ]);
}
}

