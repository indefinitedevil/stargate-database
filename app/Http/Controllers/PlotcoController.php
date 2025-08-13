<?php

namespace App\Http\Controllers;

use App\Mail\DowntimeReminder;
use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\Downtime;
use App\Models\DowntimeAction;
use App\Models\Event;
use App\Models\LogType;
use App\Models\SkillCategory;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;

class PlotcoController extends Controller
{
    public function characters(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $baseCharacters = Character::with('user')->join('users', 'characters.user_id', '=', 'users.id')
            ->select('characters.*')
            ->orderBy('users.name')
            ->orderBy('characters.name');
        $activeCharacters = (clone $baseCharacters)->whereIn('status_id', [Status::APPROVED, Status::PLAYED]);
        $playerCharacters = (clone $activeCharacters)->where('user_id', '!=', User::PLOT_CO_ID);
        return view('plotco.characters', [
            'newCharacters' => (clone $baseCharacters)->where('status_id', Status::READY)->get(),
            'activeCharacters' => $activeCharacters->get(),
            'heroCharacters' => (clone $playerCharacters)->where('hero_scoundrel', Character::HERO)->get(),
            'scoundrelCharacters' => (clone $playerCharacters)->where('hero_scoundrel', Character::SCOUNDREL)->get(),
            'unknownCharacters' => (clone $playerCharacters)->where('hero_scoundrel', Character::UNKNOWN)->get(),
            'villainCharacters' => (clone $playerCharacters)->where('hero_scoundrel', Character::VILLAIN)->get(),
            'plotcoCharacters' => (clone $activeCharacters)->where('user_id', User::PLOT_CO_ID)->get(),
            'inactiveCharacters' => (clone $baseCharacters)->whereIn('status_id', [Status::DEAD, Status::RETIRED, Status::INACTIVE])->get(),
        ]);
    }

    public function skills(Request $request)
    {
        if ($request->user()->cannot('viewSkills', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        if ($request->has('event')) {
            $characters = Event::where('id', $request->get('event'))->first()->characters()->whereIn('status_id', [Status::APPROVED, Status::PLAYED])->sortBy('name')->pluck('id');
        } else {
            $characters = Character::whereIn('status_id', [Status::APPROVED, Status::PLAYED])->orderBy('name')->get()->pluck('id');
        }
        $currentEvents = Event::where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();
        $skillCategories = SkillCategory::with('skills')
            ->where('id', '!=', SkillCategory::SYSTEM)
            ->get();
        return view('plotco.skills', [
            'validCharacters' => $characters,
            'currentEvents' => $currentEvents,
            'skillCategories' => $skillCategories,
        ]);
    }

    public function attendance(Request $request)
    {
        if ($request->user()->cannot('view attendance')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $currentEvents = Event::where('end_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->get();
        $pastEvents = Event::where('end_date', '<', now())
            ->orderBy('start_date', 'asc')
            ->get();
        return view('plotco.attendance', [
            'currentEvents' => $currentEvents,
            'pastEvents' => $pastEvents,
        ]);
    }

    public function printAll(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('characters.print', [
            'characters' => Character::with('user')->whereIn('status_id', [Status::READY, Status::APPROVED, Status::PLAYED])->orderBy('name')->get(),
        ]);
    }

    public function printSome(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        if ($request->has('characters')) {
            $characters = Character::with('user')->whereIn('id', $request->get('characters'))->orderBy('name')->get();
        } elseif ($request->has('event')) {
            $characters = Event::where('id', $request->get('event'))->first()->characters()->sortBy('name');
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
            'downtimes' => Downtime::orderBy('start_time', 'desc')->paginate(4),
        ]);
    }

    public function remindDowntime(Request $request, $downtimeId)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $downtime = Downtime::findOrFail($downtimeId);
        foreach (User::all() as $user) {
            Mail::to($user->email, $user->name)->send(new DowntimeReminder($downtime, $user));
            if ('local' == env('APP_ENV') || str_contains($_SERVER['HTTP_HOST'], 'herokuapp.com')) {
                break;
            }
        }
        return redirect(route('plotco.downtimes.preprocess', [
            'downtimeId' => $downtimeId,
        ]))->with('success', new MessageBag([__('Downtime reminder sent successfully.')]));
    }

    public function deleteDowntimeActions(Request $request, $downtimeId, $characterId)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $downtime = Downtime::findOrFail($downtimeId);
        if ($downtime->processed) {
            return redirect(route('plotco.downtimes'))
                ->with('errors', new MessageBag([__('Downtime already processed.')]));
        }
        $actions = DowntimeAction::where('downtime_id', $downtimeId)
            ->where('character_id', $characterId)
            ->get();
        foreach ($actions as $action) {
            $action->delete();
        }
        $character = Character::findOrFail($characterId);
        return redirect(route('plotco.downtimes'))
            ->with('success', new MessageBag([__('Downtime actions for :character deleted successfully.', ['character' => $character->listName])]));
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

    public function logs(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.logs', [
            'logs' => CharacterLog::with(['character', 'character.user', 'user', 'logType', 'skill'])
                ->where('log_type_id', LogType::PLOT)
                ->orderBy('created_at', 'desc')
                ->paginate(30),
        ]);
    }
}
