<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class DivisionController extends Controller
{
    public function index()
    {
        return view('organisation.divisions.index');
    }

    public function edit($divisionId)
    {
        $division = Division::findOrFail($divisionId);
        return view('organisation.divisions.edit', ['division' => $division]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'division_head' => 'nullable|exists:characters,id',
            'division_second' => 'nullable|exists:characters,id',
            'division_staff' => 'nullable|exists:characters,id',
        ]);

        if ($request->has('id')) {
            $division = Division::findOrFail($request->input('id'));
            $division->update($data);
            $message = 'Division updated successfully.';
        } else {
            $division = Division::create($data);
            $message = 'Division created successfully.';
        }
        $members = [];
        if ($request->get('division_head', 0)) {
            $members[$request->input('division_head')] = ['position' => Division::HEAD];
        }
        if ($request->get('division_second', 0)) {
            $members[$request->input('division_second')] = ['position' => Division::SECOND];
        }
        if ($request->get('division_staff', 0)) {
            $members[$request->input('division_staff')] = ['position' => Division::STAFF];
        }
        $division->characters()->sync($members);

        return redirect()->route('divisions.index')->with('success', new MessageBag([__($message)]));
    }
}
