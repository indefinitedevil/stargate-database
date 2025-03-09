<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Event;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
        return view('events.index');
    }

    public function attendance(Request $request, $eventId)
    {
        if ($request->user()->cannot('view attendance')) {
            return redirect(route('dashboard'));
        }
        return view('events.attendance', [
            'event' => Event::findOrFail($eventId),
        ]);
    }

    public function create(Request $request) {
        if ($request->user()->cannot('edit events')) {
            return redirect(route('dashboard'));
        }
        return view('events.edit', [
            'event' => new Event(),
        ]);
    }

    public function edit(Request $request, $eventId) {
        if ($request->user()->cannot('edit events')) {
            return redirect(route('dashboard'));
        }
        return view('events.edit', [
            'event' => Event::findOrFail($eventId),
        ]);
    }

    public function storeAttendance(Request $request) {
        if ($request->user()->cannot('record attendance')) {
            return redirect(route('dashboard'));
        }
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'attendance' => 'required|array',
        ]);
        $event = Event::findOrFail($request->get('event_id'));
        $attendance = $request->get('attendance');
        $eventsData = [];
        foreach ($attendance as $userId => $data) {
            $user = User::findOrFail($userId);
            $data['attended'] = !empty($data['attended']) && $data['attended'] == 'on';
            $eventsData[$user->id] = $data;
        }
        $event->users()->sync($eventsData);

        return redirect(route('events.attendance', $event->id));
    }

    public function store(Request $request) {
        if ($request->user()->cannot('edit events')) {
            return redirect(route('dashboard'));
        }
        $validatedData = $request->validate([
            'id' => 'sometimes|exists:events,id',
            'name' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);
        if (!empty($validatedData['id'])) {
            $event = Event::findOrFail($validatedData['id']);
        } else {
            $event = new Event();
        }
        if ($validatedData['start_date'] > $validatedData['end_date']) {
            return redirect()->back()->withErrors(['start_date' => 'Start date must be before end date.']);
        }
        $event->fill($validatedData);
        $event->save();
        return redirect(route('events.edit', $event->id));
    }
}
