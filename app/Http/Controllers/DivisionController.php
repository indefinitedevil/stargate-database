<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        return view('organisation.divisions.index');
    }
}
