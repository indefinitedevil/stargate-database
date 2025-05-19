<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->where('id', User::PLOT_CO_ID)
            ->update([
                'name' => 'Plot Coordinator',
                'email' => 'plotcoordinator_sglrp@hotmail.co.uk',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
