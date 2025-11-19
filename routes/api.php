<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizBank\MaterialController;
use App\Http\Controllers\Api\QuizBank\QuestionController;
use App\Http\Controllers\Api\QuizBank\QuestionSetController;
use App\Http\Controllers\Api\Room\RoomController;
use App\Http\Controllers\Api\Room\ParticipantController;
use App\Http\Controllers\Api\Room\AnswerController;
use App\Http\Controllers\Api\Room\LeaderboardController;

    // --------------- Register and Login ----------------//
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
    
    // ------------------ Get Data ----------------------//
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logOut']);
        Route::get('get-user', [UserController::class, 'userInfo']);
        Route::apiResource('materials', MaterialController::class)->except(['show']);
        Route::apiResource('questions', QuestionController::class)->except(['index']);
        Route::apiResource('question-sets', QuestionSetController::class);
        Route::post('rooms', [RoomController::class, 'store']); // create room
        Route::post('rooms/{room}/start', [RoomController::class, 'start']);
        Route::post('rooms/{room}/finish', [RoomController::class, 'finish']);
    });

    // Public endpoints (students can call)
    Route::post('rooms/join', [ParticipantController::class, 'join']); // join without auth
    Route::post('rooms/{room}/answers', [AnswerController::class, 'submit']); // submit answer
    Route::get('rooms/{room}/questions', [RoomController::class, 'questions']); // get room questions
    Route::get('rooms/{room}/leaderboard', [LeaderboardController::class, 'index']); // get room leaderboard