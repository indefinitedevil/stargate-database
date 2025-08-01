<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class TeamController extends Controller
{
    public function index()
    {
        return view('organisation.teams.index');
    }

    public function create()
    {
        return view('organisation.teams.edit');
    }

    public function edit($teamId)
    {
        $team = Team::findOrFail($teamId);
        return view('organisation.teams.edit', ['team' => $team]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'team_lead' => 'nullable|exists:characters,id',
            'team_second' => 'nullable|exists:characters,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'nullable|exists:characters,id',
        ]);

        if ($request->has('id')) {
            $team = Team::findOrFail($request->input('id'));
            $team->update($data);
            $message = 'Team updated successfully.';
        } else {
            $team = Team::create($data);
            $message = 'Team created successfully.';
        }
        $members = [];
        foreach ($request->input('team_members', []) as $memberId) {
            if ($memberId) {
                $members[$memberId] = ['position' => 0];
            }
        }
        if ($request->has('team_lead')) {
            $members[$request->input('team_lead')] = ['position' => Team::LEAD];
        }
        if ($request->has('team_second')) {
            $members[$request->input('team_second')] = ['position' => Team::SECOND];
        }
        $team->characters()->sync($members);

        return redirect()->route('teams.index')->with('success', new MessageBag([__($message)]));
    }

    public function view($teamId)
    {
        $team = Team::findOrFail($teamId);
        return view('organisation.teams.view', ['team' => $team]);
    }
}
