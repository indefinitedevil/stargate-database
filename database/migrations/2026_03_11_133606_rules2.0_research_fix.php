<?php

use App\Models\ResearchProject;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $skillUpdate = [
            46 => [95, 96, 97], // Biology
            48 => [105], // Chemistry
            49 => [98, 99], // Computers
            50 => [100, 101], // Engineering
            55 => [102, 103], // Maths
            56 => [105], // Physics
        ];
        foreach ($skillUpdate as $original_skill_id => $updates) {
            $researchProjects = ResearchProject::where('status', ResearchProject::STATUS_ACTIVE)
                ->join('research_project_skill', 'research_project_skill.research_project_id', '=', 'research_projects.id')
                ->where('research_project_skill.skill_id', $original_skill_id)
                ->select('research_projects.*')
                ->get();
            foreach ($researchProjects as $researchProject) {
                $skills = array_unique(array_merge($researchProject->skills->pluck('id')->toArray(), $updates));
                $researchProject->skills()->sync($skills);
            }
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
