<?php

namespace Database\Seeders;

use App\Models\ActionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $actionTypes = [
            [
                'id' => ActionType::TRAINING,
                'type' => ActionType::DEVELOPMENT,
                'name' => 'Training'
            ],
            [
                'id' => ActionType::TEACHING,
                'type' => ActionType::DEVELOPMENT,
                'name' => 'Teaching'
            ],
            [
                'id' => ActionType::UPKEEP,
                'type' => ActionType::DEVELOPMENT,
                'name' => 'Upkeep'
            ],
            [
                'id' => ActionType::MISSION,
                'type' => ActionType::DEVELOPMENT,
                'name' => 'Mission'
            ],
            [
                'id' => ActionType::RESEARCHING,
                'type' => ActionType::RESEARCH,
                'name' => 'Researching'
            ],
            [
                'id' => ActionType::UPKEEP_2,
                'type' => ActionType::RESEARCH,
                'name' => 'Upkeep'
            ],
            [
                'id' => ActionType::OTHER,
                'type' => ActionType::MISC,
                'name' => 'Other'
            ],
            [
                'id' => ActionType::EXPERIMENT,
                'type' => ActionType::RESEARCH_SUBJECT,
                'name' => 'Research Subject'
            ],
        ];

        DB::table('action_types')->upsert($actionTypes, 'id');
    }
}
