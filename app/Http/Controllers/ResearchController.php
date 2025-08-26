<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class ResearchController extends Controller
{
    public function index()
    {
        $publicProjects = ResearchProject::where('visibility', ResearchProject::VISIBILITY_PUBLIC);
        $privateProjects = ResearchProject::where('visibility', ResearchProject::VISIBILITY_PRIVATE)
            ->selectRaw('research_projects.*, 4 AS sort_order');
        $archivedProjects = ResearchProject::where('visibility', ResearchProject::VISIBILITY_ARCHIVED)
            ->selectRaw('research_projects.*, 5 AS sort_order');
        $activeProjects = (clone $publicProjects)->where('status', ResearchProject::STATUS_ACTIVE)
            ->selectRaw('research_projects.*, 1 AS sort_order')
            ->orderBy('name');
        $approvedProjects = (clone $publicProjects)->where('status', ResearchProject::STATUS_APPROVED)
            ->selectRaw('research_projects.*, 2 AS sort_order')
            ->orderBy('name');
        $otherProjects = (clone $publicProjects)->whereNotIn('status', [ResearchProject::STATUS_ACTIVE, ResearchProject::STATUS_APPROVED])
            ->selectRaw('research_projects.*, 3 AS sort_order')
            ->orderBy('status')
            ->orderBy('name');
        if (empty(request()->input('as_player')) && auth()->user()->can('edit research projects')) {
            $approvedProjects = $approvedProjects->union((clone $privateProjects)->where('status', ResearchProject::STATUS_APPROVED)->orderBy('name'));
            $approvedProjects = $approvedProjects->union((clone $archivedProjects)->where('status', ResearchProject::STATUS_APPROVED)->orderBy('name'));
            $otherProjects = $otherProjects->union((clone $privateProjects)->whereNotIn('status', [ResearchProject::STATUS_ACTIVE, ResearchProject::STATUS_APPROVED])
                ->orderBy('status')
                ->orderBy('name'));
            $otherProjects = $otherProjects->union((clone $archivedProjects)->whereNotIn('status', [ResearchProject::STATUS_ACTIVE, ResearchProject::STATUS_APPROVED])
                ->orderBy('status')
                ->orderBy('name'));
        }
        $projects = $activeProjects->union($approvedProjects)->union($otherProjects)
            ->orderBy('sort_order')->orderBy('name')->paginate(18);
        return view('research.index', compact('projects'));
    }

    public function view($projectId)
    {
        $project = ResearchProject::findOrFail($projectId);
        if (ResearchProject::VISIBILITY_PRIVATE == $project->visibility && !auth()->user()->can('edit research projects')) {
            return redirect(route('research.index'))
                ->with('error', __('You do not have permission to view this research project.'));
        }
        return view('research.view', compact('project'));
    }

    public function create()
    {
        $parentProjects = ResearchProject::where('visibility', '!=', ResearchProject::VISIBILITY_PRIVATE)
            ->where('status', ResearchProject::STATUS_COMPLETED)
            ->orderBy('name')
            ->get();
        return view('research.edit', compact('parentProjects'));
    }

    public function edit($projectId)
    {
        $project = ResearchProject::findOrFail($projectId);
        if (ResearchProject::STATUS_COMPLETED == $project->status && !auth()->user()->can('approve research projects')) {
            return redirect($project->getViewRoute())
                ->with('error', __('Completed projects cannot be edited.'));
        }
        $parentProjects = ResearchProject::where('visibility', '!=', ResearchProject::VISIBILITY_PRIVATE)
            ->where('status', ResearchProject::STATUS_COMPLETED)
            ->orderBy('name')
            ->get();
        return view('research.edit', compact('project', 'parentProjects'));
    }

    /**
     * @throws ValidationException
     */
    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'research_subject' => 'required|string|max:255',
            'project_goals' => 'required|string',
            'ooc_intent' => 'required|string',
            'results' => 'string|nullable',
            'plot_notes' => 'string|nullable',
            'months' => 'integer',
            'status' => 'required|integer',
            'visibility' => 'required|integer',
            'needs_volunteers' => 'boolean',
            'parent_project_id' => 'nullable|exists:research_projects,id',
            'skills' => 'array',
            'skills.*' => 'exists:skills,id',
            'specialty_id' => 'array',
            'specialty_id.*' => 'exists:skill_specialties,id',
        ], [], [
            'ooc_intent' => 'OOC Intent',
        ]);

        $validationMessages = [];
        if (ResearchProject::STATUS_ACTIVE == $data['status'] && ResearchProject::VISIBILITY_PUBLIC != $data['visibility']) {
            $validationMessages['visibility'] = __('Active research projects must be public.');
        }
        if (!empty($data['skills']) && !empty($data['months']) && count($data['skills']) > $data['months']) {
            $validationMessages['months'] = __('Research projects must be long enough to cover all skills required.');
        }

        if (count($validationMessages)) {
            throw ValidationException::withMessages($validationMessages);
        }

        $researchProject = ResearchProject::updateOrCreate(
            ['id' => request('id')],
            $data
        );
        if (!empty($data['skills'])) {
            $researchProject->skills()->sync($data['skills']);
        }
        if (!empty($data['specialty_id'])) {
            $researchProject->skillSpecialties()->sync($data['specialty_id']);
        }
        $message = request('id') ? 'Research project updated successfully.' : 'Research project created successfully.';
        return redirect($researchProject->getViewRoute())->with('success', new MessageBag([__($message)]));
    }
}
