<?php

namespace App\Http\Controllers;

use App\Models\ResearchProject;

class ResearchController extends Controller
{
    public function index()
    {
        if (auth()->user()->can('edit research projects')) {
            $baseProjects = ResearchProject::query();
        } else {
            $baseProjects = ResearchProject::where('visibility', ResearchProject::VISIBILITY_PUBLIC);
        }
        $activeProjects = (clone $baseProjects)->where('status', ResearchProject::STATUS_ACTIVE)
            ->orderBy('name');
        $approvedProjects = (clone $baseProjects)->where('status', ResearchProject::STATUS_APPROVED)
            ->orderBy('name');
        $otherProjects = (clone $baseProjects)->whereNotIn('status', [ResearchProject::STATUS_ACTIVE, ResearchProject::STATUS_APPROVED])
            ->orderBy('status')
            ->orderBy('name');
        $projects = $activeProjects->union($approvedProjects)->union($otherProjects)->paginate(12);
        return view('research.index', compact('projects'));
    }

    public function view($id)
    {
        $project = ResearchProject::findOrFail($id);
        return view('research.view', compact('project'));
    }

    public function create()
    {
        return view('research.edit');
    }

    public function edit($projectId)
    {
        $project = ResearchProject::findOrFail($projectId);
        return view('research.edit', compact('project'));
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'research_subject' => 'required|string|max:255',
            'project_goals' => 'required|string',
            'ooc_intent' => 'nullable|string',
            'results' => 'nullable|string',
            'plot_notes' => 'nullable|string',
            'months' => 'required|integer|min:1',
            'status' => 'required|integer',
            'visibility' => 'required|integer',
            'needs_volunteers' => 'boolean',
            'parent_project_id' => 'nullable|exists:research_projects,id',
        ]);

        if (ResearchProject::STATUS_ACTIVE == $data['status'] && ResearchProject::VISIBILITY_PUBLIC != $data['visibility']) {
            return redirect()->back()
                ->withErrors(['visibility' => __('Active research projects must be public.')])
                ->withInput();
        }

        $researchProject = ResearchProject::updateOrCreate(
            ['id' => request('id')],
            $data
        );
        $message = request('id') ? 'Research project updated successfully.' : 'Research project created successfully.';
        return redirect()->route('research.view', ['id' => $researchProject->id])
            ->with('success', __($message));
    }
}
