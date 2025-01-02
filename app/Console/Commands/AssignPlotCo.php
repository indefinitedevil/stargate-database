<?php

namespace App\Console\Commands;

class AssignPlotCo extends AssignRole
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sg:assign:plot-co {user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a user the plot co role';

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
        $user->assignRole('plot coordinator');
        echo 'Assigned plot coordinator role to ' . $user->name . PHP_EOL;
        return 0;
    }
}
