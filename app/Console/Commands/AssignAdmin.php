<?php

namespace App\Console\Commands;

class AssignAdmin extends AssignRole
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sg:assign:admin {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a user the admin role';

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
        $user->assignRole('admin');
        echo 'Assigned admin role to ' . $user->name . PHP_EOL;
        return 0;
    }
}
