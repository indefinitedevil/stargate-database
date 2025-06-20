<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            $eventRunner = Role::findByName('event runner');
            $eventRunner->delete();
        } catch (RoleDoesNotExist) {
            // Role does not exist, nothing to remove
            return;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
