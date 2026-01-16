<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;

abstract class Controller
{
    protected function compareModelNames(Model $model1, Model $model2)
    {
        if ($model1->name == $model2->name) {
            return 0;
        }
        return ($model1->name < $model2->name) ? -1 : 1;
    }
}
