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
            $room->load('material')
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
        $questions = $this->service->getQuestions($room->id);

        return response()->json([
            'room' => $room->load('material'),
            'questions' => $questions
        ]);
    }

    public function gameQuestions(Room $room): JsonResponse
    {
        // Fetch questions using the service
        $questions = $this->service->getQuestions($room->id);

        // Sanitize questions to hide answers
        $sanitizedQuestions = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'type' => $question->type,
                'options' => $question->options->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'option_text' => $option->option_text,
                        // 'is_correct' is intentionally omitted
                    ];
                }),
                // 'correct_answer' is intentionally omitted
            ];
        });

        return response()->json([
            'room' => $room->makeHidden(['teacher', 'participants', 'answers']), // Hide sensitive room info if necessary
            'questions' => $sanitizedQuestions
        ]);
    }

    public function getRoomCount(): JsonResponse
    {
        $count = Room::where('user_id', Auth::id())->count();
        return response()->json($count);
    }

    public function destroy(Room $room): JsonResponse
    {
        if ($room->user_id !== Auth::id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->service->destroy($room);

        return response()->json([
            'message' => 'Room deleted successfully'
        ]);
    }   
}

