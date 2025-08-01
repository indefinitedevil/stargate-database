<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function create()
    {
        return view('organisation.departments.edit');
    }
    public function edit($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        return view('organisation.departments.edit', ['department' => $department]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Department::create($data);

        return redirect()->route('organisation.departments.index')->with('success', 'Department created successfully.');
    }

    public function view($departmentId)
    {
        $department = Department::findOrFail($departmentId);
        return view('organisation.departments.view', ['department' => $department]);
    }

    public function organisation()
    {
        return view('organisation.chart');
    }
}
