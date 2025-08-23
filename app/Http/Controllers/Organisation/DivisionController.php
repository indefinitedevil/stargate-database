<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class DivisionController extends Controller
{
    public function index()
    {
        return view('organisation.divisions.index', [
            'divisions' => Division::all(),
        ]);
    }

    public function edit($divisionId)
    {
        return view('organisation.divisions.edit', [
            'division' => Division::findOrFail($divisionId),
            'activeCharacters' => Character::getActiveCharacters(),
        ]);
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

        $roles = array_filter([
            $request->input('division_head'),
            $request->input('division_second'),
            $request->input('division_staff'),
        ]);
        if (count($roles) !== count(array_unique($roles))) {
            return redirect()->back()->withErrors(new MessageBag([__('The same character cannot be assigned to multiple roles. Please select different characters for each role.')]));
        }

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
