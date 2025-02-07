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
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('events.attendance', [
            'event' => Event::findOrFail($eventId),
        ]);
    }

    public function create() {
        return redirect('events.index');
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
}
