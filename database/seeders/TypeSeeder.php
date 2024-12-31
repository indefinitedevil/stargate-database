<?php

namespace Database\Seeders;

use App\Models\LogType;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed specialty types
        DB::table('specialty_types')->insertOrIgnore([
            ['id' => 1, 'name' => 'Archeo-Anthropology'],
            ['id' => 2, 'name' => 'Medical'],
            ['id' => 3, 'name' => 'Language'],
            ['id' => 4, 'name' => 'Mythology'],
        ]);
        // seed card types
        DB::table('card_types')->upsert([
            ['id' => 1, 'name' => 'Computing'],
            ['id' => 2, 'name' => 'Cryptography'],
            ['id' => 3, 'name' => 'Elec. Eng.'],
            ['id' => 4, 'name' => 'Explosives'],
            ['id' => 5, 'name' => 'Larceny'],
            ['id' => 6, 'name' => 'Mech. Eng.'],
            ['id' => 7, 'name' => 'Paramedic'],
            ['id' => 8, 'name' => 'SIGINT'],
        ], 'id');
        // seed log types
        DB::table('log_types')->insertOrIgnore([
            ['id' => LogType::CHARACTER_CREATION, 'name' => 'Character Creation'],
            ['id' => LogType::DOWNTIME, 'name' => 'Downtime'],
            ['id' => LogType::PLOT, 'name' => 'Plot'],
        ]);
        // seed discount types
        DB::table('discount_types')->insertOrIgnore([
            ['id' => 1, 'name' => 'Skill'],
            ['id' => 2, 'name' => 'Teacher'],
            ['id' => 3, 'name' => 'Plot'],
        ]);
        // seed status
        DB::table('statuses')->upsert([
            ['id' => Status::NEW, 'name' => 'New'],
            ['id' => Status::APPROVED, 'name' => 'Approved'],
            ['id' => Status::PLAYED, 'name' => 'Played'],
            ['id' => Status::DEAD, 'name' => 'Dead'],
            ['id' => Status::RETIRED, 'name' => 'Retired'],
        ], 'id');
    }
}
