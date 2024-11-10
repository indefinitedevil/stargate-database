<?php

namespace Database\Seeders;

use App\Models\CharacterSkill;
use App\Models\Skill;
use App\Models\SkillSpecialty;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['id' => Skill::ARCHEO_ANTHROPOLOGY, 'name' => 'Archeo-Anthropology'],
            ['id' => 2, 'name' => 'Medical'],
            ['id' => 3, 'name' => 'Language'],
            ['id' => 4, 'name' => 'Mythology'],
        ]);
        // seed card types
        DB::table('card_types')->insertOrIgnore([
            ['id' => 1, 'name' => 'Computing'],
            ['id' => 2, 'name' => 'Cryptography'],
            ['id' => 3, 'name' => 'Electrical Engineering'],
            ['id' => 4, 'name' => 'Explosives Training'],
            ['id' => 5, 'name' => 'Larceny'],
            ['id' => 6, 'name' => 'Mechanical Engineering'],
            ['id' => 7, 'name' => 'Paramedic'],
            ['id' => 8, 'name' => 'Signals Intelligence'],
        ]);
        // seed log types
        DB::table('log_types')->insertOrIgnore([
            ['id' => 1, 'name' => 'Character Creation'],
            ['id' => 2, 'name' => 'Downtime'],
            ['id' => 3, 'name' => 'Plot'],
        ]);
        // seed discount types
        DB::table('discount_types')->insertOrIgnore([
            ['id' => 1, 'name' => 'Skill'],
            ['id' => 2, 'name' => 'Teacher'],
            ['id' => 3, 'name' => 'Plot'],
        ]);
        // seed status
        DB::table('statuses')->insertOrIgnore([
            ['id' => Status::NEW, 'name' => 'New'],
            ['id' => Status::PLAYED, 'name' => 'Played'],
            ['id' => Status::DEAD, 'name' => 'Dead'],
            ['id' => Status::RETIRED, 'name' => 'Retired'],
        ]);
    }
}
