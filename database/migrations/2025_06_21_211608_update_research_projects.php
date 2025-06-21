<?php

use Database\Seeders\ActionTypeSeeder;
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
        Schema::table('research_project_skill', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->dropColumn('months');
        });

        Schema::table('research_projects', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->string('research_subject')->after('name');
            $table->text('project_goals')->after('research_subject');
            $table->text('ooc_intent')->after('project_goals');
            $table->text('results')->after('ooc_intent');
            $table->text('plot_notes')->after('results');
            $table->boolean('approved')->default(false)->after('plot_notes');
            $table->boolean('active')->default(false)->after('approved');
            $table->boolean('public')->default(false)->after('active');
            $table->boolean('archived')->default(false)->after('public');
            $table->boolean('completed')->default(false)->after('archived');
            $table->boolean('needs_volunteers')->default(false)->after('completed');
            $table->foreignId('parent_project_id')
                ->nullable()
                ->after('needs_volunteers')
                ->constrained('research_projects')
                ->nullOnDelete();
        });

        $seeder = new ActionTypeSeeder();
        $seeder->run();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
