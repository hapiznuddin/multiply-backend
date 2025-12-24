<?php

namespace App\Http\Controllers\Api\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Achievement;
use App\Models\RoomParticipant;
use App\Models\UserAchievement;
use Illuminate\Http\JsonResponse;

class AchievementController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Achievement::all());
    }

    public function userAchievements(RoomParticipant $participant): JsonResponse
    {
        $achievements = UserAchievement::where('room_participant_id', $participant->id)
            ->with('achievement')
            ->get()
            ->pluck('achievement');

        return response()->json($achievements);
    }
}
