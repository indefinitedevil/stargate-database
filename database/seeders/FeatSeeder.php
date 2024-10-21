<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed feats
        DB::table('feats')->insert([
            [
                'id' => 1,
                'name' => 'Dodge!',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 2,
                'name' => 'Die Hard',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 3,
                'name' => 'Flash of Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 4,
                'name' => 'Total Focus',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 5,
                'name' => 'Last Heroic Act',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 6,
                'name' => 'All Guns Blazing',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 7,
                'name' => 'A Very Distinctive...',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 8,
                'name' => 'Bodyguard',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 9,
                'name' => 'Botch Job',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 10,
                'name' => 'Cat-like Reflexes',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 11,
                'name' => 'Codebreaker',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 12,
                'name' => 'Cauterise',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 13,
                'name' => 'Drug Resistance',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 14,
                'name' => 'Escape Artist',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 15,
                'name' => 'Firm Grip',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 16,
                'name' => 'Fly It Like You Stole It',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 17,
                'name' => 'Get Back In The Fight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 18,
                'name' => 'Hunker Down',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 19,
                'name' => 'Explosives Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 20,
                'name' => 'Electrical Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 21,
                'name' => 'Computing Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 22,
                'name' => 'Communications Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 23,
                'name' => 'Mechanical Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 24,
                'name' => 'Larceny Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 25,
                'name' => 'Medical Insight',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 26,
                'name' => 'Interrogator',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 27,
                'name' => 'Killing Blow',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 28,
                'name' => 'Emergency Measures',
                'description' => '',
                'per_event' => 1
            ],
            [
                'id' => 29,
                'name' => 'Marksman',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 30,
                'name' => 'Natural Resistance',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 31,
                'name' => 'Negotiator',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 32,
                'name' => 'Numb3rs',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 33,
                'name' => 'Old College Professor',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 34,
                'name' => 'On Your Feet Soldier',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 35,
                'name' => 'Polyglot',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 36,
                'name' => 'Technical Mentor',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 37,
                'name' => 'Tick Tock Motherfucker',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 38,
                'name' => 'Tomb Raider',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 39,
                'name' => 'Torture Resistance',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 40,
                'name' => 'Tracker',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 41,
                'name' => 'We Have A Job To Do',
                'description' => '',
                'per_event' => 0
            ],
            [
                'id' => 42,
                'name' => 'Cryptographic Insight',
                'description' => '',
                'per_event' => 0
            ],
        ]);

        // seed background feats
        DB::table('background_feat')->insert([
            [
                'background_id' => 1,
                'feat_id' => 1
            ],
            [
                'background_id' => 1,
                'feat_id' => 2
            ],
            [
                'background_id' => 1,
                'feat_id' => 3
            ],
            [
                'background_id' => 1,
                'feat_id' => 4
            ],
            [
                'background_id' => 1,
                'feat_id' => 5
            ],
            [
                'background_id' => 2,
                'feat_id' => 1
            ],
            [
                'background_id' => 2,
                'feat_id' => 2
            ],
            [
                'background_id' => 2,
                'feat_id' => 3
            ],
            [
                'background_id' => 2,
                'feat_id' => 4
            ],
            [
                'background_id' => 2,
                'feat_id' => 5
            ],
            [
                'background_id' => 3,
                'feat_id' => 1
            ],
            [
                'background_id' => 3,
                'feat_id' => 2
            ],
            [
                'background_id' => 3,
                'feat_id' => 3
            ],
            [
                'background_id' => 3,
                'feat_id' => 4
            ],
            [
                'background_id' => 3,
                'feat_id' => 5
            ],
        ]);

        // seed skill feats
        DB::table('feat_skill')->insert([
            [
                'skill_id' => 1,
                'feat_id' => 9
            ],
            [
                'skill_id' => 2,
                'feat_id' => 11
            ],
            [
                'skill_id' => 2,
                'feat_id' => 42
            ],
            [
                'skill_id' => 3,
                'feat_id' => 9
            ],
            [
                'skill_id' => 3,
                'feat_id' => 20
            ],
            [
                'skill_id' => 4,
                'feat_id' => 9
            ],
            [
                'skill_id' => 4,
                'feat_id' => 19
            ],
            [
                'skill_id' => 5,
                'feat_id' => 14
            ],
            [
                'skill_id' => 5,
                'feat_id' => 24
            ],
            [
                'skill_id' => 6,
                'feat_id' => 9
            ],
            [
                'skill_id' => 6,
                'feat_id' => 23
            ],
            [
                'skill_id' => 7,
                'feat_id' => 17
            ],
            [
                'skill_id' => 7,
                'feat_id' => 25
            ],
            [
                'skill_id' => 8,
                'feat_id' => 9
            ],
            [
                'skill_id' => 8,
                'feat_id' => 22
            ],
            [
                'skill_id' => 9,
                'feat_id' => 38
            ],
            [
                'skill_id' => 11,
                'feat_id' => 3
            ],
            [
                'skill_id' => 12,
                'feat_id' => 13
            ],
            [
                'skill_id' => 12,
                'feat_id' => 30
            ],
            [
                'skill_id' => 14,
                'feat_id' => 35
            ],
            [
                'skill_id' => 15,
                'feat_id' => 37
            ],
            [
                'skill_id' => 16,
                'feat_id' => 28
            ],
            [
                'skill_id' => 21,
                'feat_id' => 32
            ],
            [
                'skill_id' => 22,
                'feat_id' => 17
            ],
            [
                'skill_id' => 22,
                'feat_id' => 37
            ],
            [
                'skill_id' => 24,
                'feat_id' => 10
            ],
            [
                'skill_id' => 24,
                'feat_id' => 34
            ],
            [
                'skill_id' => 27,
                'feat_id' => 7
            ],
            [
                'skill_id' => 27,
                'feat_id' => 8
            ],
            [
                'skill_id' => 28,
                'feat_id' => 33
            ],
            [
                'skill_id' => 29,
                'feat_id' => 40
            ],
            [
                'skill_id' => 30,
                'feat_id' => 3
            ],
            [
                'skill_id' => 32,
                'feat_id' => 7
            ],
            [
                'skill_id' => 33,
                'feat_id' => 26
            ],
            [
                'skill_id' => 35,
                'feat_id' => 41
            ],
            [
                'skill_id' => 36,
                'feat_id' => 28
            ],
            [
                'skill_id' => 38,
                'feat_id' => 31
            ],
            [
                'skill_id' => 42,
                'feat_id' => 3
            ],
            [
                'skill_id' => 43,
                'feat_id' => 13
            ],
            [
                'skill_id' => 43,
                'feat_id' => 39
            ],
            [
                'skill_id' => 44,
                'feat_id' => 16
            ],
            [
                'skill_id' => 60,
                'feat_id' => 30
            ],
            [
                'skill_id' => 69,
                'feat_id' => 40
            ],
            [
                'skill_id' => 71,
                'feat_id' => 12
            ],
            [
                'skill_id' => 73,
                'feat_id' => 27
            ],
            [
                'skill_id' => 73,
                'feat_id' => 29
            ],
            [
                'skill_id' => 75,
                'feat_id' => 18
            ],
            [
                'skill_id' => 76,
                'feat_id' => 6
            ],
            [
                'skill_id' => 78,
                'feat_id' => 10
            ],
            [
                'skill_id' => 78,
                'feat_id' => 27
            ],
            [
                'skill_id' => 81,
                'feat_id' => 10
            ],
            [
                'skill_id' => 82,
                'feat_id' => 15
            ],
            [
                'skill_id' => 82,
                'feat_id' => 27
            ],
            [
                'skill_id' => 87,
                'feat_id' => 28
            ],
            [
                'skill_id' => 88,
                'feat_id' => 36
            ],
        ]);
    }
}
