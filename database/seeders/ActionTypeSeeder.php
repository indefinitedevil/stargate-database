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
                'id' => ActionType::ACTION_TRAINING,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Training'
            ],
            [
                'id' => ActionType::ACTION_TEACHING,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Teaching'
            ],
            [
                'id' => ActionType::ACTION_UPKEEP,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Upkeep'
            ],
            [
                'id' => ActionType::ACTION_MISSION,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Mission'
            ],
            [
                'id' => ActionType::ACTION_RESEARCHING,
                'type' => ActionType::TYPE_RESEARCH,
                'name' => 'Researching'
            ],
            [
                'id' => ActionType::ACTION_UPKEEP_2,
                'type' => ActionType::TYPE_RESEARCH,
                'name' => 'Upkeep'
            ],
            [
                'id' => ActionType::ACTION_OTHER,
                'type' => ActionType::TYPE_MISC,
                'name' => 'Other'
            ],
            [
                'id' => ActionType::ACTION_RESEARCH_SUBJECT,
                'type' => ActionType::TYPE_EXPERIMENT,
                'name' => 'Research Subject'
            ],
        ];

        DB::table('action_types')->upsert($actionTypes, 'id');
    }
}
