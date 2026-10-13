<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted')) {
                $table->boolean('deleted')->default(false)->after('email');
            }
        });
        $users = User::where('name', 'LIKE', '%-deleted')->get();
        foreach ($users as $user) {
            $user->deleted = true;
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'deleted')) {
                $table->dropColumn('deleted');
            }
        });
    }
};
