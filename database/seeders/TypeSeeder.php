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
            [1, 'Archeo-Anthropology'],
            [2, 'Medical'],
            [3, 'Language'],
            [4, 'Mythology'],
        ]);
        // seed card types
        DB::table('card_types')->insert([
            [1,'Computing'],
            [2,'Cryptography'],
            [3,'Electrical Engineering'],
            [4,'Explosives Training'],
            [5,'Larceny'],
            [6,'Mechanical Engineering'],
            [7,'Paramedic'],
            [8,'Signals Intelligence'],
        ]);
        // seed log types
        DB::table('log_types')->insert([
            [1, 'Character Creation'],
            [2, 'Downtime'],
            [3, 'Plot'],
        ]);
        // seed discount types
        DB::table('discount_types')->insert([
            [1, 'Skill'],
            [2, 'Teacher'],
            [3, 'Plot'],
        ]);
        // seed status
        DB::table('status')->insert([
            [0, 'New'],
            [1, 'Played'],
            [2, 'Dead'],
        ]);
    }
}
