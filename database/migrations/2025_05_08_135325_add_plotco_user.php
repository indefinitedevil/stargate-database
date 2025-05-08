<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $user = User::find(1);
        $user->name = 'Plot Coordinator';
        $user->email = 'plotcoordinator_sglrp@hotmail.co.uk';
        $user->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
