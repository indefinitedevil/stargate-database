<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        DB::table('users')->insertOrIgnore([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        */
        DB::table('users')->upsert([
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make(Random::generate()),
            ]
        ], 'email');
        foreach (User::all() as $user) {
            $user->assignRole('player');
        }
    }
}
