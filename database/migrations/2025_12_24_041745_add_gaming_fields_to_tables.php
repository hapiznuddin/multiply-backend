<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('time_limit_per_question')->default(30)->after('status');
        });

        Schema::table('room_participants', function (Blueprint $table) {
            $table->integer('current_streak')->default(0)->after('score');
            $table->integer('max_streak')->default(0)->after('current_streak');
            $table->integer('total_time')->default(0)->after('max_streak');
        });

        Schema::table('room_answers', function (Blueprint $table) {
            $table->integer('time_taken')->nullable()->after('is_correct');
            $table->integer('speed_bonus')->default(0)->after('time_taken');
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('time_limit_per_question');
        });

        Schema::table('room_participants', function (Blueprint $table) {
            $table->dropColumn(['current_streak', 'max_streak', 'total_time']);
        });

        Schema::table('room_answers', function (Blueprint $table) {
            $table->dropColumn(['time_taken', 'speed_bonus']);
        });
    }
};
