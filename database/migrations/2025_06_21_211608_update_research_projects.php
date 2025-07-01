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
            $table->tinyInteger('months')->change()->default(0);
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
        Schema::table('research_project_skill', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('months')->change()->default(1);
        });

        Schema::table('research_projects', function (Blueprint $table) {
            $table->dropColumn('research_subject');
            $table->dropColumn('project_goals');
            $table->dropColumn('ooc_intent');
            $table->dropColumn('results');
            $table->dropColumn('plot_notes');
            $table->dropColumn('months');
            $table->dropColumn('status');
            $table->dropColumn('visibility');
            $table->dropColumn('needs_volunteers');
            $table->dropForeign(['parent_project_id']);
            $table->dropColumn('parent_project_id');
        });

        Schema::table('downtimes', function (Blueprint $table) {
            $table->dropColumn('experiment_actions');
        });
    }
};
