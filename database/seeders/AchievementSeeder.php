<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'name' => 'First Blood',
                'description' => 'Jawaban benar pertama kamu!',
                'icon' => 'target',
                'badge_type' => 'bronze',
                'requirement_type' => 'participation',
                'requirement_value' => 1,
            ],
            [
                'name' => 'Hot Streak',
                'description' => 'Menjawab 5 pertanyaan benar berturut-turut.',
                'icon' => 'flame',
                'badge_type' => 'silver',
                'requirement_type' => 'streak',
                'requirement_value' => 5,
            ],
            [
                'name' => 'Speed Demon',
                'description' => 'Menjawab dalam waktu kurang dari 3 detik.',
                'icon' => 'zap',
                'badge_type' => 'gold',
                'requirement_type' => 'speed',
                'requirement_value' => 3,
            ],
            [
                'name' => 'Score Master',
                'description' => 'Mencapai total skor 1000 poin.',
                'icon' => 'trophy',
                'badge_type' => 'gold',
                'requirement_type' => 'score',
                'requirement_value' => 1000,
            ],
            [
                'name' => 'Unstoppable',
                'description' => 'Menjawab 10 pertanyaan benar berturut-turut.',
                'icon' => 'shield-check',
                'badge_type' => 'platinum',
                'requirement_type' => 'streak',
                'requirement_value' => 10,
            ],
        ];

        foreach ($achievements as $ach) {
            Achievement::create($ach);
        }
    }
}
