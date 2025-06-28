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
                'id' => 1,
                'action' => ActionType::ACTION_TRAINING,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Training'
            ],
            [
                'id' => 2,
                'action' => ActionType::ACTION_TEACHING,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Teaching'
            ],
            [
                'id' => 3,
                'action' => ActionType::ACTION_UPKEEP,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Upkeep'
            ],
            [
                'id' => 4,
                'action' => ActionType::ACTION_MISSION,
                'type' => ActionType::TYPE_DEVELOPMENT,
                'name' => 'Mission'
            ],
            [
                'id' => 5,
                'action' => ActionType::ACTION_RESEARCHING,
                'type' => ActionType::TYPE_RESEARCH,
                'name' => 'Researching'
            ],
            [
                'id' => 6,
                'action' => ActionType::ACTION_UPKEEP,
                'type' => ActionType::TYPE_RESEARCH,
                'name' => 'Upkeep'
            ],
            [
                'id' => 7,
                'action' => ActionType::ACTION_OTHER,
                'type' => ActionType::TYPE_MISC,
                'name' => 'Other'
            ],
            [
                'id' => 8,
                'action' => ActionType::ACTION_RESEARCH_SUBJECT,
                'type' => ActionType::TYPE_EXPERIMENT,
                'name' => 'Research Subject'
            ],
        ];

        DB::table('action_types')->upsert($actionTypes, ['id']);
    }
}
