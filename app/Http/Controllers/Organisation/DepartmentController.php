<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Department;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class DepartmentController extends Controller
{
    public function create()
    {
        return view('organisation.departments.edit');
    }

    public function edit($departmentId)
    {
        return view('organisation.departments.edit', [
            'department' => Department::findOrFail($departmentId),
            'activeCharacters' => Character::getActiveCharacters(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department_head' => 'nullable|exists:characters,id',
            'department_specialists' => 'nullable|array',
            'department_specialists.*' => 'nullable|exists:characters,id',
            'department_members' => 'nullable|array',
            'department_members.*' => 'nullable|exists:characters,id',
        ]);

        if ($request->has('id')) {
            $department = Department::findOrFail($request->input('id'));
            $department->update($data);
            $message = 'Department updated successfully.';
        } else {
            $department = Department::create($data);
            $message = 'Department created successfully.';
        }
        $members = [];
        foreach ($request->input('department_members', []) as $memberId) {
            if ($memberId) {
                $members[$memberId] = ['position' => 0];
            }
        }
        if ($request->get('department_head', 0)) {
            $members[$request->input('department_head')] = ['position' => Department::HEAD];
        }
        foreach ($request->input('department_specialists', []) as $memberId) {
            if ($memberId) {
                $members[$memberId] = ['position' => Department::SPECIALIST];
            }
        }
        $department->characters()->sync($members);

        return redirect()->route('divisions.index')->with('success', new MessageBag([__($message)]));
    }

    public function view($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        return view('organisation.departments.view', ['department' => $department]);
    }

    public function organisation()
    {
        return view('organisation.chart', [
            'divisions' => Division::with(['characters', 'departments.characters'])->get(),
        ]);
    }
}
