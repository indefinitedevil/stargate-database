<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BackgroundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seed backgrounds
        DB::table('backgrounds')->insert([
            [
                'id' => 1,
                'name' => 'Military',
                'description' => 'You are a serving member of a NATO or Allied Armed Force. You have a particular area of expertise which is why you have been assigned to the program.. Bringing your specialist skills and combat expertise to the SEF you can work under heavy fire on alien planets.',
                'body' => 10,
                'vigor' => 10,
                'months' => 36,
            ],
            [
                'id' => 2,
                'name' => 'Intelligence',
                'description' => 'You are a member of a NATO or Allied Intelligence Service such as MI5, MI6, or other similar 3 letter agencies. This would also cover members of Police services such as the FBI or Interpol.',
                'body' => 10,
                'vigor' => 10,
                'months' => 36,
            ],
            [
                'id' => 3,
                'name' => 'Civilian',
                'description' => 'Neither serving in the Military, nor employed by an Intelligence Service, you are a civilian, recruited into the SEF for your unique or expert specialist knowledge or experience in your field.',
                'body' => 10,
                'vigor' => 10,
                'months' => 36,
            ],
        ]);
    }
}
