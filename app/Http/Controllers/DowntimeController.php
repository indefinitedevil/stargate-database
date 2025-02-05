<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Downtime;
use App\Models\Status;
use Illuminate\Http\Request;

class DowntimeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('view own character')) {
            return redirect(route('dashboard'));
        }
        return view('downtimes.index', [
            'downtimes' => $request->user()->downtimes(),
            'activeCharacters' => Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get(),
        ]);
    }

    public function submit(Request $request, $downtimeId, $characterId)
    {
        if ($request->user()->cannot('view own character')) {
            return redirect(route('dashboard'));
        }
        $downtime = Downtime::find($downtimeId);
        $character = Character::find($characterId);
        return view('downtimes.submit', [
            'downtime' => $downtime,
            'character' => $character,
        ]);
    }

    public function view(Request $request, $downtimeId, $characterId)
    {
        if ($request->user()->cannot('view own character')) {
            return redirect(route('dashboard'));
        }
        $downtime = Downtime::find($downtimeId);
        $character = Character::find($characterId);
        return view('downtimes.view', [
            'downtime' => $downtime,
            'characters' => $character,
        ]);
    }

    public function store(Request $request)
    {
        return redirect('downtimes.index');
    }
}
