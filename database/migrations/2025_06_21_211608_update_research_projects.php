<?php

use App\Models\ResearchProject;
use Database\Seeders\ActionTypeSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('research_project_skill', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('research_projects', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->string('research_subject')->after('name');
            $table->text('project_goals')->after('research_subject');
            $table->text('ooc_intent')->after('project_goals');
            $table->text('results')->nullable()->after('ooc_intent');
            $table->text('plot_notes')->nullable()->after('results');
            $table->tinyInteger('months')->default(0)->after('plot_notes');
            $table->tinyInteger('status')->default(ResearchProject::STATUS_PENDING)->after('months');
            $table->tinyInteger('visibility')->default(ResearchProject::VISIBILITY_PRIVATE)->after('status');
            $table->boolean('needs_volunteers')->default(false)->after('visibility');
            $table->foreignId('parent_project_id')->nullable()->after('needs_volunteers')
                ->constrained('research_projects')->nullOnDelete();
        });

        Schema::table('downtimes', function (Blueprint $table) {
            $table->tinyInteger('experiment_actions')->default(1)->after('research_actions');
        });

        $seeder = new ActionTypeSeeder();
        $seeder->run();

        $seeder = new RoleSeeder();
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
