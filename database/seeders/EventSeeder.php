<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'id' => 1,
                'name' => 'Event 0',
                'start_date' => '2025-01-17',
                'end_date' => '2025-01-19',
                'description' => 'Selected candidates are invited to learn about the Stargate program. Also the AGM.',
                'location' => 'Sherbrooke Campsite',
            ],
            [
                'id' => 2,
                'name' => 'Event 1',
                'start_date' => '2025-05-02',
                'end_date' => '2025-05-05',
                'description' => 'The first deployment through the Stargate.',
                'location' => 'TBC',
            ],
            [
                'id' => 3,
                'name' => 'Event 2',
                'start_date' => '2025-08-08',
                'end_date' => '2025-08-10',
                'description' => 'The second deployment through the Stargate.',
                'location' => 'Silverwood Campsite',
            ],
            [
                'id' => 4,
                'name' => 'Event 3',
                'start_date' => '2025-11-07',
                'end_date' => '2025-11-09',
                'description' => 'The third deployment through the Stargate.',
                'location' => 'John\'s Lee Wood',
            ],
        ];
        DB::table('events')->upsert($events, 'id');
    }
}
