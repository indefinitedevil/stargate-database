<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Event;
use App\Models\Status;
use Illuminate\Http\Request;

class PlotcoController extends Controller
{
    public function characters(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('plotco.characters', [
            'newCharacters' => Character::where('status_id', Status::READY)->orderBY('name', 'asc')->get(),
            'activeCharacters' => Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get(),
            'inactiveCharacters' => Character::whereIn('status_id', [Status::DEAD, Status::RETIRED])->orderBy('name', 'asc')->get(),
        ]);
    }

    public function skills(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        if ($request->has('event')) {
            $characters = Event::where('id', $request->get('event'))->first()->characters()->whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get()->pluck('id');
        } else {
            $characters = Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get()->pluck('id');
        }
        return view('plotco.skills', [
            'validCharacters' => $characters,
        ]);
    }

    public function attendance(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('plotco.attendance');
    }

    public function printAll(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('characters.print', [
            'characters' => Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get(),
        ]);
    }

    public function printSome(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        if ($request->has('characters')) {
            $characters = Character::whereIn('id', $request->get('characters'))->orderBy('name', 'asc')->get();
        } elseif ($request->has('event')) {
            $characters = Event::where('id', $request->get('event'))->first()->characters()->orderBy('name', 'asc')->get();
        } else {
            $characters = [];
        }
        return view('characters.print', [
            'characters' => $characters,
        ]);
    }
}
