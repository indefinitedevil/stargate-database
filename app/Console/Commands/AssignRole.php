<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sg:assign:role {user} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a user the given role';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = $this->getUser();
        if (!$user) {
            $this->error('User not found');
            return 1;
        }
        $user->assignRole($this->argument('role'));
        return 0;
    }

    protected function getUser(): User
    {
        $userId = $this->argument('user');
        if (is_numeric($userId)) {
            return User::find($userId);
        } else {
            return User::where(['email' => $userId])->first();
        }
    }
}
