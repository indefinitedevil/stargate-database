<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Models\SkillSpecialty;
use App\Models\SpecialtyType;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class SpecialtyController extends Controller
{
    public function index(Request $request)
    {
        $specialties = $specialtyTypes = [];
        foreach (SpecialtyType::all() as $type) {
            $specialtyTypes[$type->id] = $type;
        }
        foreach (SkillSpecialty::all() as $specialty) {
            if (!$specialty->hidden || $request->user()->can('edit skill specialty')) {
                $specialties[$specialty->specialty_type_id][] = $specialty;
            }
        }
        uasort($specialtyTypes, [$this, 'compareModelNames']);
        foreach ($specialties as &$specialtyList) {
            usort($specialtyList, [$this, 'compareModelNames']);
        }
        return view('rules.specialties', compact('specialties', 'specialtyTypes'));
    }

    public function create()
    {
        $specialtyTypes = SpecialtyType::orderBy('name')->get();
        return view('rules.specialties/edit', compact('specialtyTypes'));
    }

    public function edit(int $specialtyId)
    {
        $specialty = SkillSpecialty::find($specialtyId);
        $specialtyTypes = SpecialtyType::orderBy('name')->get();
        return view('rules.specialties/edit', compact('specialty', 'specialtyTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'sometimes|exists:skill_specialties,id|nullable|int',
            'name' => 'required|string|max:255',
            'specialty_type_id' => 'required|exists:specialty_types,id|int',
            'hidden' => 'boolean',
        ]);
        if ($request->has('id')) {
            $specialty = SkillSpecialty::find($request->input('id'));
        } else {
            $specialty = new SkillSpecialty();
        }
        $specialty->fill($validatedData);
        $specialty->save();
        return redirect(route('rules.specialties'))
            ->with('success', new MessageBag([__('Specialty :name created successfully.', ['name' => $specialty->name])]));
    }

    public function delete(int $specialtyId)
    {
        SkillSpecialty::destroy($specialtyId);
        return redirect(route('rules.specialties'))
            ->with('success', new MessageBag([__('Specialty deleted successfully.')]));
    }
}
