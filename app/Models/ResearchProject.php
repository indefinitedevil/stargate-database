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
 * @property int status
 * @property string status_name
 * @property int visibility
 * @property string visibility_name
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

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_ACTIVE = 2;
    const STATUS_ON_HOLD = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_ABANDONED = 5;

    const VISIBILITY_PRIVATE = 0;
    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_ARCHIVED = 2;

    protected $fillable = [
        'name',
        'research_subject',
        'project_goals',
        'ooc_intent',
        'results',
        'plot_notes',
        'months',
        'status',
        'visibility',
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

    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending approval',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_ON_HOLD => 'On Hold',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_ABANDONED => 'Abandoned',
            default => 'Unknown Status',
        };
    }

    public function getVisibilityNameAttribute(): string
    {
        return match ($this->visibility) {
            self::VISIBILITY_PRIVATE => 'Private',
            self::VISIBILITY_PUBLIC => 'Public',
            self::VISIBILITY_ARCHIVED => 'Archived',
            default => 'Unknown Visibility',
        };
    }
}
