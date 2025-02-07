<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Event;
use App\Models\Status;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function attendance(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('events.attendance');
    }
}
