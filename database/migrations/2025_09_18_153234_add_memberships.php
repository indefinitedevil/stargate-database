<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $manageMemberships = Permission::findOrCreate('manage memberships');
        $secretary = Role::findOrCreate('secretary');
        $secretary->givePermissionTo($manageMemberships);

        if (!Schema::hasTable('memberships')) {
            Schema::create('memberships', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->date('start_date');
                $table->date('end_date');
                $table->timestamps();
            });

            Schema::create('membership_user', function (Blueprint $table) {
                $table->foreignId('membership_id')->constrained('memberships')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('users', 'membership_name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('membership_name')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
        Schema::dropIfExists('membership_user');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('membership_name');
        });
    }
};
