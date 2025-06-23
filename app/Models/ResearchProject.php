<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string name
 * @property string research_subject
 * @property string project_goals
 * @property string ooc_intent
 * @property string results
 * @property string plot_notes
 * @property int months
 * @property bool approved
 * @property bool active
 * @property bool public
 * @property bool archived
 * @property bool completed
 * @property bool needs_volunteers
 * @property int parent_project_id
 * @property Collection|DowntimeAction[] downtimeActions
 * @property Collection|Skill[] skills
 * @property ResearchProject parentProject
 * @property Collection|ResearchProject[] childProjects
 * @property Collection|DowntimeAction[] researchActions
 * @property Collection|DowntimeAction[] subjectActions
 */
class ResearchProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'research_subject',
        'project_goals',
        'ooc_intent',
        'results',
        'plot_notes',
        'months',
        'approved',
        'active',
        'public',
        'archived',
        'completed',
        'needs_volunteers',
        'parent_project_id',
    ];

    public function downtimeActions(): HasMany
    {
        return $this->hasMany(DowntimeAction::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class)
            ->withPivot('months');
    }

    public function parentProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class, 'parent_project_id');
    }

    public function childProjects(): HasMany
    {
        return $this->hasMany(ResearchProject::class, 'parent_project_id');
    }

    public function researchActions(): HasMany
    {
        return $this->downtimeActions()->where('research_project_id', $this->id)
            ->where('action_type_id', ActionType::RESEARCHING);
    }

    public function subjectActions(): HasMany
    {
        return $this->downtimeActions()->where('research_project_id', $this->id)
            ->where('action_type_id', ActionType::RESEARCH_SUBJECT);
    }
}
