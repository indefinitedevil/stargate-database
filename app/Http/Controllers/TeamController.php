<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class TeamController extends Controller
{
    public function index()
    {
        return view('organisation.teams.index', [
            'permanentTeams' => Team::whereNull('event_id')->orderBy('name')->get(),
            'eventTeams' => Team::where('event_id', Event::nextEventId())->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('organisation.teams.edit', [
            'events' => Event::all(),
        ]);
    }

    public function edit($teamId)
    {
        $team = Team::findOrFail($teamId);
        return view('organisation.teams.edit', [
            'team' => $team,
            'events' => Event::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_id' => 'nullable|exists:events,id',
            'team_lead' => 'nullable|exists:characters,id',
            'team_second' => 'nullable|exists:characters,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'nullable|exists:characters,id',
        ]);

        $roles = array_filter([
            $request->input('team_lead'),
            $request->input('team_second'),
        ]);
        if (count($roles) !== count(array_unique($roles))) {
            return redirect()->back()->withErrors(new MessageBag([__('The same character cannot be assigned to multiple roles. Please select different characters for each role.')]));
        }

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
        if ($request->get('team_lead', 0)) {
            $members[$request->input('team_lead')] = ['position' => Team::LEAD];
        }
        if ($request->get('team_second', 0)) {
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
