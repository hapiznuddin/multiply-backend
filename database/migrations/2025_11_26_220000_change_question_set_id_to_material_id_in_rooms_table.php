<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['question_set_id']);
            $table->renameColumn('question_set_id', 'material_id');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->renameColumn('material_id', 'question_set_id');
            $table->foreign('question_set_id')->references('id')->on('question_sets')->onDelete('cascade');
        });
    }
};
