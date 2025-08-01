<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

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
        ]);

        Team::create($data);

        return redirect()->route('organisation.teams.index')->with('success', 'Team created successfully.');
    }

    public function view($teamId)
    {
        $team = Team::findOrFail($teamId);
        return view('organisation.teams.view', ['team' => $team]);
    }
}
