<?php

namespace App\Http\Controllers;

use App\Models\CharacterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class TraitsController extends Controller
{
    public function index()
    {
        $traits = CharacterTrait::orderBy('name')->paginate(12);
        return view('plotco.traits.index', compact('traits'));
    }

    public function create()
    {
        return view('plotco.traits.edit');
    }

    public function edit($traitId)
    {
        $trait = CharacterTrait::findOrFail($traitId);
        return view('plotco.traits.edit', compact('trait'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'string',
            'chance' => 'required|integer|min:0|max:100',
            'revealed' => 'boolean',
        ]);

        if ($request->has('id')) {
            $trait = CharacterTrait::findOrFail($request->input('id'));
        } else {
            $trait = new CharacterTrait();
        }
        $trait->fill($validatedData);
        $trait->save();

        return redirect()->route('plotco.traits')
            ->with('success', new MessageBag([__(request('id') ? 'Trait updated successfully.' : 'Trait created successfully.')]));
    }

    public function delete($traitId)
    {
        $trait = CharacterTrait::findOrFail($traitId);
        $trait->delete();

        return redirect()->route('traits.index')->with('success', __('Trait deleted successfully.'));
    }
}
