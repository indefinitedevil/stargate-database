<?php

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
        Schema::table('characters', function (Blueprint $table) {
            if (Schema::hasColumn('characters', 'pronouns')) {
                return;
            }
            $table->string('pronouns', 16)->nullable()->after('name');
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'pronouns')) {
                return;
            }
            $table->string('pronouns', 16)->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('characters', 'pronouns')) {
            Schema::table('characters', function (Blueprint $table) {
                $table->dropColumn('pronouns');
            });
        }

        if (Schema::hasColumn('users', 'pronouns')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('pronouns');
            });
        }
    }
};
