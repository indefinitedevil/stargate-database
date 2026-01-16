<?php

namespace App\Http\Controllers\Rules;

use App\Http\Controllers\Controller;
use App\Models\SkillSpecialty;
use App\Models\SpecialtyType;
use Illuminate\Database\Eloquent\Model;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = $specialtyTypes = [];
        foreach (SpecialtyType::all() as $type) {
            $specialtyTypes[$type->id] = $type;
        }
        foreach (SkillSpecialty::all() as $specialty) {
            $specialties[$specialty->specialty_type_id][] = $specialty;
        }
        uasort($specialtyTypes, [$this, 'compareModelNames']);
        foreach ($specialties as &$specialtyList) {
            usort($specialtyList, [$this, 'compareModelNames']);
        }
        return view('rules.specialties', compact('specialties', 'specialtyTypes'));
    }

    protected function compareModelNames(Model $model1, Model $model2)
    {
        if ($model1->name == $model2->name) {
            return 0;
        }
        return ($model1->name < $model2->name) ? -1 : 1;
    }
}
