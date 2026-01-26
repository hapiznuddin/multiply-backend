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
            $table->string('title')->after('code');                    // sesuai request rule
            $table->integer('max_players')->nullable()->after('title'); // sesuai request rule
            $table->datetime('starts_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('max_players');
            $table->dropColumn('starts_at');
        });
    }
};
