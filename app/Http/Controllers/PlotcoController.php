<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Downtime;
use App\Models\Event;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class PlotcoController extends Controller
{
    public function characters(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.characters', [
            'newCharacters' => Character::where('status_id', Status::READY)->orderBY('name', 'asc')->get(),
            'activeCharacters' => Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get(),
            'inactiveCharacters' => Character::whereIn('status_id', [Status::DEAD, Status::RETIRED])->orderBy('name', 'asc')->get(),
        ]);
    }

    public function skills(Request $request)
    {
        if ($request->user()->cannot('view skill breakdown')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        if ($request->has('event')) {
            $characters = Event::where('id', $request->get('event'))->first()->characters()->whereIn('status_id', [Status::APPROVED, Status::PLAYED])->sortBy('name')->pluck('id');
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
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.attendance');
    }

    public function printAll(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('characters.print', [
            'characters' => Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name', 'asc')->get(),
        ]);
    }

    public function printSome(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
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

    public function downtimes(Request $request)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.downtimes.index', [
            'downtimes' => Downtime::orderBy('start_time', 'desc')->get(),
        ]);
    }

    public function preprocessDowntime(Request $request, $downtimeId)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.downtimes.preprocess', [
            'downtime' => Downtime::findOrFail($downtimeId),
        ]);
    }

    public function processDowntime(Request $request, $downtimeId)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $downtime = Downtime::findOrFail($downtimeId);
        if ($downtime->open || now()->lt($downtime->end_time)) {
            return redirect(route('plotco.downtimes.preprocess', [
                'downtimeId' => $downtimeId,
            ]))->with('errors', new MessageBag([__('Downtime has not closed.')]));
        }
        if ($downtime->processed) {
            return redirect(route('plotco.downtimes.preprocess', [
                'downtimeId' => $downtimeId,
            ]))->with('errors', new MessageBag([__('Downtime already processed.')]));
        }
        $downtime->process();
        return redirect(route('plotco.downtimes.preprocess', [
            'downtimeId' => $downtimeId,
        ]))->with('success', new MessageBag([__('Downtime processed successfully.')]));
    }
}
