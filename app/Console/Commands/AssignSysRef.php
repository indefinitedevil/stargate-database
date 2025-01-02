<?php

namespace App\Console\Commands;

class AssignSysRef extends AssignRole
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sg:assign:sys-ref {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a user the sys ref role';

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
        $user->assignRole('system referee');
        echo 'Assigned system referee role to ' . $user->name . PHP_EOL;
        return 0;
    }
}
