<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Room;
use App\Models\RoomParticipant;

class LeaderboardController extends Controller
{
public function index(Room $room): JsonResponse
{
    $leaderboard = RoomParticipant::where('room_id', $room->id)
        ->select('id', 'participant_name', 'score')
        ->orderByDesc('score')
        ->get();

    return response()->json($leaderboard);
}
}
