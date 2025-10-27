<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\CharacterSkill;
use App\Models\Event;
use App\Models\LogType;
use App\Models\Skill;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $currentEvents = Event::where('end_date', '>=', today())
            ->orderBy('start_date', 'asc')
            ->get();
        $pastEvents = Event::where('end_date', '<', today())
            ->orderBy('start_date', 'asc')
            ->get();
        return view('events.index', [
            'currentEvents' => $currentEvents,
            'pastEvents' => $pastEvents,
        ]);
    }

    public function attendance(Request $request, $eventId)
    {
        if ($request->user()->cannot('view attendance')) {
            return redirect(route('dashboard'));
        }
        $event = Event::findOrFail($eventId);
        $roles = [Event::ROLE_PLAYER, Event::ROLE_RUNNER, Event::ROLE_CREW, Event::ROLE_PAID_DOWNTIME];$eventRoles = $attended = $characters = [];
        foreach ($event->users as $user) {
            $eventRoles[$user->id] = $user->pivot->role;
            $attended[$user->id] = $user->pivot->attended;
            $characters[$user->id] = $user->pivot->character_id;
        }
        $users = User::with('characters')->orderBy('name', 'asc')->get();
        return view('events.attendance', compact(
            'event',
            'roles',
            'eventRoles',
            'attended',
            'characters',
            'users',
        ));
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('edit events')) {
            return redirect(route('dashboard'));
        }
        return view('events.edit', [
            'event' => new Event(),
        ]);
    }

    public function edit(Request $request, $eventId)
    {
        if ($request->user()->cannot('edit events')) {
            return redirect(route('dashboard'));
        }
        return view('events.edit', [
            'event' => Event::findOrFail($eventId),
        ]);
    }

    public function storeAttendance(Request $request)
    {
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
            $data['role'] = (int) $data['role'] ?? Event::ROLE_NONE;
            $eventsData[$user->id] = $data;
            if ($data['attended'] && in_array($data['role'], [Event::ROLE_PLAYER, Event::ROLE_PAID_DOWNTIME]) && !empty($data['character_id'])) {
                $character = Character::findOrFail($data['character_id']);
                if (Status::APPROVED == $character->status_id) {
                    $character->status_id = Status::PLAYED;
                    $character->save();
                }
                if (Event::ROLE_PLAYER == $data['role']) {
                    $tempBody = $character->temp_body;
                    $tempVigor = $character->temp_vigor;
                    if ($tempBody > 0 || $tempVigor > 0) {
                        $skill = new CharacterSkill();
                        $skill->fill([
                            'character_id' => $character->id,
                            'skill_id' => Skill::SYSTEM_CHANGE,
                            'completed' => true,
                        ]);
                        $skill->save();
                        $log = new CharacterLog();
                        $log->fill([
                            'character_id' => $character->id,
                            'character_skill_id' => $skill->id,
                            'log_type_id' => LogType::SYSTEM,
                            'amount_trained' => 0,
                            'temp_body_change' => -1 * $tempBody,
                            'temp_vigor_change' => -1 * $tempVigor,
                            'locked' => 1,
                            'notes' => __('Resetting temporary stats after event attendance.'),
                        ]);
                        $log->save();
                    }
                }
            }
        }
        $event->users()->sync($eventsData);

        return redirect(route('events.attendance', $event->id));
    }

    public function store(Request $request)
    {
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
