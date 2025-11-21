<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Room;

class LeaderboardController extends Controller
{
    public function index(Room $room): JsonResponse
    {
        $board = $room->participants()
            ->orderByDesc('score')
            ->get(['id','name','score']);

        return response()->json(['leaderboard' => $board]);
    }
}
