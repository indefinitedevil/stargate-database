<?php

namespace App\Http\Controllers;

use App\Mail\DowntimeCreated;
use App\Models\ActionType;
use App\Models\Character;
use App\Models\CharacterSkill;
use App\Models\Downtime;
use App\Models\DowntimeAction;
use App\Models\DowntimeMission;
use App\Models\Event;
use App\Models\ResearchProject;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class DowntimeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('view own character')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('downtimes.index', [
            'downtimes' => Downtime::orderBy('start_time', 'desc')->get(),
            'characterIds' => $request->user()->characters->pluck('id'),
            'activeCharacterIds' => $request->user()->characters->whereIn('status_id', [Status::APPROVED, Status::PLAYED])->pluck('id'),
        ]);
    }

    public function submit(Request $request, $downtimeId, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        if ($character->status_id < Status::APPROVED) {
            return redirect()->back()->withErrors([__('Character is not approved.')]);
        }
        $downtime = Downtime::find($downtimeId);
        if (empty($downtime)) {
            return redirect()->back()->withErrors([__('Downtime is not available.')]);
        }
        return view('downtimes.submit', [
            'downtime' => $downtime,
            'character' => $character,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.downtimes.edit', [
            'downtime' => new Downtime(),
        ]);
    }

    public function edit(Request $request, $downtimeId)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $downtime = Downtime::find($downtimeId);
        return view('plotco.downtimes.edit', [
            'downtime' => $downtime,
        ]);
    }

    public function viewSubmission(Request $request, $downtimeId, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $downtime = Downtime::find($downtimeId);
        return view('downtimes.view', [
            'downtime' => $downtime,
            'character' => $character,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        $validatedData = $request->validate([
            'id' => 'sometimes|exists:downtimes,id|nullable|int',
            'name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'development_actions' => 'required|int',
            'research_actions' => 'required|int',
            'other_actions' => 'required|int',
            'event_id' => 'sometimes|exists:events,id|nullable|int',
        ]);
        if (!empty($validatedData['event_id'])) {
            $event = Event::find($validatedData['event_id']);
            if (empty($event)) {
                throw ValidationException::withMessages(['event_id' => __('Event not found.')]);
            }
            $downtimes = Downtime::where('event_id', $event->id);
            if (!empty($validatedData['id'])) {
                $downtimes = $downtimes->where('id', '!=', $validatedData['id']);
            }
            $downtimes = $downtimes->get();
            if ($downtimes->count() > 0) {
                throw ValidationException::withMessages(['event_id' => __('Event already has a downtime.')]);
            }
        }
        $validatedData['start_time'] = utc_datetime($validatedData['start_time']);
        $validatedData['end_time'] = utc_datetime($validatedData['end_time']);
        $downtime = empty($validatedData['id']) ? new Downtime() : Downtime::find($validatedData['id']);
        $downtime->fill($validatedData);
        $downtime->save();
        if (empty($validatedData['id'])) {
            foreach (User::all() as $user) {
                Mail::to($user->email, $user->name)->send(new DowntimeCreated($downtime, $user));
            }
        }
        return redirect(route('plotco.downtimes'))
            ->with('success', new MessageBag([__('Downtime saved successfully.')]));
    }

    /**
     * @throws ValidationException
     */
    public function storeSubmission(Request $request)
    {
        $errors = [];
        $character = Character::find($request->input('character_id'));
        if (empty($character)) {
            $errors[] = __('Character not found.');
        } elseif (in_array($character->status_id, [Status::NEW, Status::READY])) {
            $errors[] = __('Character is not approved.');
        } elseif (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            $errors[] = __('Character is not in play.');
        }
        $downtime = Downtime::find($request->input('downtime_id'));
        if (empty($downtime)) {
            $errors[] = __('Downtime not found.');
        } elseif (!$downtime->isOpen()) {
            $errors[] = __('Downtime is not open.');
        }
        if (empty($errors)) {
            $developmentActions = $request->get('development_action');
            $this->validateActions($developmentActions ?? [], $errors, $character, $downtime, 'Development');
            $researchActions = $request->get('research_action');
            $this->validateActions($researchActions ?? [], $errors, $character, $downtime, 'Research');
            $otherActions = $request->get('other_action');
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
        return redirect(route('downtimes.submit', [
            'downtimeId' => $downtime->id,
            'characterId' => $character->id,
        ]))
            ->with('success', new MessageBag([__('Downtime actions saved successfully')]));
    }

    protected function validateActions(array $actions, array &$errors, Character $character, Downtime $downtime, string $type)
    {
        foreach ($actions as $key => $actionData) {
            switch ($actionData['type']) {
                case ActionType::TRAINING:
                case ActionType::TEACHING:
                case ActionType::UPKEEP:
                case ActionType::UPKEEP_2:
                    if (empty($actionData['skill_id'])) {
                        $errors[] = __(':type Action :index: Skill is required.', ['type' => $type, 'index' => $key]);
                    } else {
                        if (!empty($actionData['notes']) && strlen($actionData['notes']) > 65535) {
                            $errors[] = __(':type Action :index: Notes are limited to 65000 characters.', ['type' => $type, 'index' => $key]);
                        } else {
                            $characterSkill = CharacterSkill::find($actionData['skill_id']);
                            if (empty($characterSkill)) {
                                $errors[] = __(':type Action :index: Skill not found.', ['type' => $type, 'index' => $key]);
                            } else {
                                $actionId = $actionData['id'] ?? null;
                                if (!empty($actionId)) {
                                    $action = DowntimeAction::find($actionId);
                                    if (empty($action)) {
                                        $errors[] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                                    }
                                } else {
                                    $action = new DowntimeAction();
                                }
                                $action->fill([
                                    'character_id' => $character->id,
                                    'downtime_id' => $downtime->id,
                                    'action_type_id' => $actionData['type'],
                                    'character_skill_id' => $characterSkill->id,
                                    'notes' => $actionData['notes'] ?? '',
                                ]);
                                $action->save();
                            }
                        }
                    }
                    break;
                case ActionType::OTHER:
                    $actionId = $actionData['id'] ?? null;
                    if (!empty($actionData['notes']) && strlen($actionData['notes']) > 65535) {
                        $errors[] = __(':type Action :index: Notes are limited to 65000 characters.', ['type' => $type, 'index' => $key]);
                    } else {
                        if (!empty($actionId)) {
                            $action = DowntimeAction::find($actionId);
                            if (empty($action)) {
                                $errors[] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                            }
                        } else {
                            $action = new DowntimeAction();
                        }
                        $action->fill([
                            'character_id' => $character->id,
                            'downtime_id' => $downtime->id,
                            'action_type_id' => $actionData['type'],
                            'notes' => $actionData['notes'] ?? '',
                        ]);
                        $action->save();
                    }
                    break;
                case ActionType::RESEARCHING:
                    $researchProject = ResearchProject::find($actionData['research_project_id'] ?? 0);
                    if (empty($researchProject)) {
                        $errors[] = __(':type Action :index: Research Project not found.', ['type' => $type, 'index' => $key]);
                    }
                    if (!empty($actionData['notes']) && strlen($actionData['notes']) > 65535) {
                        $errors[] = __(':type Action :index: Notes are limited to 65000 characters.', ['type' => $type, 'index' => $key]);
                    }
                    break;
                case ActionType::MISSION:
                    $mission = DowntimeMission::find($actionData['mission_id'] ?? 0);
                    if (empty($mission)) {
                        $errors[] = __(':type Action :index: Mission not found.', ['type' => $type, 'index' => $key]);
                    }
                    if (!empty($actionData['notes']) && strlen($actionData['notes']) > 65535) {
                        $errors[] = __(':type Action :index: Notes are limited to 65000 characters.', ['type' => $type, 'index' => $key]);
                    }
                    break;
                case 0:
                    break;
                default:
                    $errors[] = __(':type Action :index: Invalid action type.', ['type' => $type, 'index' => $key]);
                    break;
            }
        }
    }
}
