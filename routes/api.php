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
        Route::apiResource('questions', QuestionController::class);
        Route::apiResource('question-sets', QuestionSetController::class);
        Route::model('material', \App\Models\Material::class);
        Route::get('materials/count', [MaterialController::class, 'getCountMaterial']);
        Route::get('material/{material}/questions', [QuestionController::class, 'byMaterial']);
        Route::get('material/{material}/questions/multiple-choice', [QuestionController::class, 'multipleChoice']);
        Route::get('material/{material}/questions/input', [QuestionController::class, 'input']);
        Route::delete('question/{id}', [QuestionController::class,'destroy']);
        Route::prefix('rooms')->group(function () {
            Route::get('/', [RoomController::class, 'index']);
            Route::post('/', [RoomController::class, 'store']);
            Route::get('/count', [RoomController::class, 'getRoomCount']);
            Route::get('/{room}', [RoomController::class, 'show']);
            Route::post('/{room}/start', [RoomController::class, 'start']);
            Route::post('/{room}/finish', [RoomController::class, 'finish']);
            Route::get('/{room}/questions', [RoomController::class, 'questions']);
            Route::delete('/{room}', [RoomController::class, 'destroy']);
        });
    });

    // Public endpoints (students can call)
    Route::post('rooms/join', [ParticipantController::class, 'join']); // join without auth
    Route::post('rooms/{room}/answers', [AnswerController::class, 'submit']); // submit answer
    Route::get('rooms/{room}/questions', [RoomController::class, 'questions']); // get room questions
    Route::get('rooms/{room}/leaderboard', [LeaderboardController::class, 'index']); // get room leaderboard