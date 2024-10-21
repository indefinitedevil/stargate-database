<?php

namespace Database\Seeders;

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
        DB::table('specialty_types')->insert([
            ['id' => 1, 'name' => 'Archeo-Anthropology'],
            ['id' => 2, 'name' => 'Medical'],
            ['id' => 3, 'name' => 'Language'],
            ['id' => 4, 'name' => 'Mythology'],
        ]);
        // seed card types
        DB::table('card_types')->insert([
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
        DB::table('log_types')->insert([
            ['id' => 1, 'name' => 'Character Creation'],
            ['id' => 2, 'name' => 'Downtime'],
            ['id' => 3, 'name' => 'Plot'],
        ]);
        // seed discount types
        DB::table('discount_types')->insert([
            ['id' => 1, 'name' => 'Skill'],
            ['id' => 2, 'name' => 'Teacher'],
            ['id' => 3, 'name' => 'Plot'],
        ]);
        // seed status
        DB::table('status')->insert([
            ['id' => 1, 'name' => 'New'],
            ['id' => 2, 'name' => 'Played'],
            ['id' => 3, 'name' => 'Dead'],
        ]);
    }
}
