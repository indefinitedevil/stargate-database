<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('character_team', function (Blueprint $table) {
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(0);
            $table->primary(['team_id', 'character_id']);
        });
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('character_division', function (Blueprint $table) {
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(0);
            $table->primary(['division_id', 'character_id']);
        });
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('division_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        Schema::create('character_department', function (Blueprint $table) {
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('character_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('position')->default(0);
            $table->primary(['department_id', 'character_id']);
        });

        DB::table('divisions')->insert([
            [
                'id' => 1,
                'name' => 'Command',
                'description' => 'Command',
            ],
            [
                'id' => 2,
                'name' => 'External Relations',
                'description' => 'External Relations Division',
            ],
            [
                'id' => 3,
                'name' => 'Research',
                'description' => 'Research Division',
            ],
            [
                'id' => 4,
                'name' => 'Field Operations',
                'description' => 'Field Operations Division',
            ],
        ]);
        DB::table('departments')->insert([
            [
                'name' => 'Diplomatic Corps',
                'division_id' => 2,
            ],
            [
                'name' => 'Intelligence',
                'division_id' => 2,
            ],
            [
                'name' => 'Humanities',
                'division_id' => 3,
            ],
            [
                'name' => 'Technology',
                'division_id' => 3,
            ],
            [
                'name' => 'Tactical',
                'division_id' => 4,
            ],
            [
                'name' => 'Support Services',
                'division_id' => 4,
            ],
        ]);
        DB::table('teams')->insert([
            [
                'name' => 'Unicorn',
                'description' => 'Command Team',
            ],
            [
                'name' => 'Siren',
                'description' => 'SAR/Medevac',
            ],
            [
                'name' => 'Cerberus',
                'description' => 'Electronic Reconnaissance',
            ],
            [
                'name' => 'Cat Sith',
                'description' => 'Cultural Investigation/Sociology',
            ],
            [
                'name' => 'Dragon',
                'description' => 'Forensics, Armoury and Engineering',
            ],
            [
                'name' => 'Sphinx',
                'description' => 'Esoterica/Specialist Knowledge',
            ],
            [
                'name' => 'Selkie',
                'description' => 'First Contact',
            ],
            [
                'name' => 'Pegasus',
                'description' => 'Conflict Resolution',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('character_department');
        Schema::drop('departments');
        Schema::drop('character_division');
        Schema::drop('divisions');
        Schema::drop('character_team');
        Schema::drop('teams');
    }
};
