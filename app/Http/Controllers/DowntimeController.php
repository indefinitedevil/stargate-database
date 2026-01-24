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
        ])->with('errors', new MessageBag($this->checkForErrors($downtime, $character)));
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('edit downtimes')) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Access not allowed.')]));
        }
        return view('plotco.downtimes.edit', [
            'downtime' => new Downtime(),
            'events' => Event::where('end_date', '<', today())->orderBy('start_date', 'desc')->get(),
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
            'events' => Event::where('end_date', '<', today())->orderBy('start_date', 'desc')->get(),
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
        ])->with('errors', new MessageBag($this->checkForErrors($downtime, $character)));
    }

    protected function checkForErrors($downtime, $character): array {
        $downtimeActions = $downtime->actions()->where('character_id', $character->id)->get();
        $development = $research = $other = 0;
        foreach ($downtimeActions as $downtimeAction) {
            switch ($downtimeAction->action_type_id) {
                case ActionType::ACTION_TRAINING:
                case ActionType::ACTION_TEACHING:
                case ActionType::ACTION_UPKEEP:
                case ActionType::ACTION_MISSION:
                    $development++;
                    break;
                case ActionType::ACTION_RESEARCHING:
                case ActionType::ACTION_UPKEEP_2:
                    $research++;
                    break;
                case ActionType::ACTION_OTHER:
                    $other++;
                    break;
            }
        }
        $errors = [];
        if ($development > $downtime->development_actions) {
            $errors['development_actions'][] = __(':character has too many development actions (:count)', ['character' => $character->listName, 'count' => $development]);
        }
        if ($research > $downtime->research_actions) {
            $errors['research_actions'][] = __(':character has too many research actions (:count)', ['character' => $character->listName, 'count' => $research]);
        }
        if ($other > $downtime->other_actions) {
            $errors['other_actions'][] = __(':character has too many personal actions (:count)', ['character' => $character->listName, 'count' => $other]);
        }
        return $errors;
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
            'experiment_actions' => 'required|int',
            'other_actions' => 'required|int',
            'event_id' => 'sometimes|exists:events,id|nullable|int',
            'response' => 'sometimes|nullable|string|max:65535',
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
                request()->flash();
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
                if ('local' == env('APP_ENV') || str_contains($_SERVER['HTTP_HOST'], 'herokuapp.com')) {
                    break;
                }
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
        } elseif ($downtime->processed) {
            $errors[] = __('Downtime is already processed.');
        } elseif (!$downtime->isOpen() && !auth()->user()->can('view hidden notes')) {
            $errors[] = __('Downtime is not open.');
        }
        if (empty($errors)) {
            if ($downtime->open) {
                $this->validateActions($request->get('development_action', []), $errors, $character, $downtime, 'Development');
                $this->validateActions($request->get('research_action', []), $errors, $character, $downtime, 'Research');
                $this->validateActions($request->get('research_subject_action', []), $errors, $character, $downtime, 'Research Subject');
            }
            $this->validateActions($request->get('other_action', []), $errors, $character, $downtime, 'Personal');
        }
        if (!empty($errors)) {
            request()->flash();
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
                case ActionType::ACTION_TRAINING:
                case ActionType::ACTION_TEACHING:
                case ActionType::ACTION_UPKEEP:
                case ActionType::ACTION_UPKEEP_2:
                    if (empty($actionData['skill_id'])) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Skill is required.', ['type' => $type, 'index' => $key]);
                    } else {
                        if (!empty($actionData['notes']) && strlen($actionData['notes']) > 2000) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Notes are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                        } elseif (!empty($actionData['response']) && strlen($actionData['response']) > 4000) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Responses are limited to 4000 characters.', ['type' => $type, 'index' => $key]);
                        } else {
                            $characterSkill = CharacterSkill::find($actionData['skill_id']);
                            if (empty($characterSkill)) {
                                $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Skill not found.', ['type' => $type, 'index' => $key]);
                            } else {
                                $actionId = $actionData['id'] ?? null;
                                if (!empty($actionId)) {
                                    $action = DowntimeAction::find($actionId);
                                    if (empty($action)) {
                                        $errors[] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                                        continue 2;
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
                                    'response' => $actionData['response'] ?? '',
                                ]);
                                $action->save();
                            }
                        }
                    }
                    break;
                case ActionType::ACTION_OTHER:
                    if (!empty($actionData['notes']) && strlen($actionData['notes']) > 2000) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Notes are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                    } elseif (!empty($actionData['response']) && strlen($actionData['response']) > 4000) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Responses are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                    } elseif (!empty($actionData['notes']) || !empty($actionData['response'])) {
                        if (!empty($actionData['id'])) {
                            $action = DowntimeAction::find($actionData['id']);
                            if (empty($action)) {
                                $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                            }
                        } else {
                            $action = new DowntimeAction();
                        }
                        $action->fill([
                            'character_id' => $character->id,
                            'downtime_id' => $downtime->id,
                            'action_type_id' => $actionData['type'],
                            'notes' => $actionData['notes'] ?? $action->notes,
                            'response' => $actionData['response'] ?? '',
                        ]);
                        $action->save();
                    } elseif (!empty($actionData['id'])) {
                        $action = DowntimeAction::find($actionData['id']);
                        $action->delete();
                    }
                    break;
                case ActionType::ACTION_RESEARCHING:
                case ActionType::ACTION_RESEARCH_SUBJECT:
                    $researchProjectId = $actionData['research_project_id'] ?? 0;
                    $characterSkillId = $actionData['skill_id'] ?? null;
                    if (empty($researchProjectId) && !empty($actionData['id'])) {
                        $action = DowntimeAction::find($actionData['id']);
                        if (empty($action)) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                        } else {
                            $action->delete();
                        }
                        continue 2;
                    }
                    if (empty($researchProjectId) && ActionType::ACTION_RESEARCH_SUBJECT == $actionData['type']) {
                        continue 2; // Skip if no research project is set for research subject action
                    }
                    if (empty($researchProjectId)) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Research Project is required.', ['type' => $type, 'index' => $key]);
                        continue 2;
                    } else {
                        $researchProject = ResearchProject::find($researchProjectId);
                        if (empty($researchProject)) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Research Project not found.', ['type' => $type, 'index' => $key]);
                            continue 2;
                        }
                    }
                    if (!empty($actionData['notes']) && strlen($actionData['notes']) > 2000) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Notes are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                        continue 2;
                    } elseif (!empty($actionData['response']) && strlen($actionData['response']) > 4000) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Responses are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                        continue 2;
                    }
                    if (ActionType::ACTION_RESEARCHING == $actionData['type']) {
                        if (empty($actionData['skill_id'])) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Skill is required.', ['type' => $type, 'index' => $key]);
                            continue 2;
                        } else {
                            $characterSkill = CharacterSkill::find($actionData['skill_id']);
                            if (empty($characterSkill)) {
                                $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Skill not found.', ['type' => $type, 'index' => $key]);
                                continue 2;
                            }
                            $researchSkills = $researchProject->skills;
                            $researchSpecialties = $researchProject->skillSpecialties;
                            if ($characterSkill->skill->specialty_type_id) {
                                $characterSpecialties = $characterSkill->allSpecialties;
                                if ($researchSpecialties->intersect($characterSpecialties)->isEmpty()) {
                                    $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Skill does not match research project specialties.', ['type' => $type, 'index' => $key]);
                                    continue 2;
                                }
                            } else {
                                if ($researchSkills->where('id', $characterSkill->skill_id)->isEmpty()) {
                                    $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Skill does not match research project skills.', ['type' => $type, 'index' => $key]);
                                    continue 2;
                                }
                            }
                        }
                    }
                    if (!empty($actionData['id'])) {
                        $action = DowntimeAction::find($actionData['id']);
                        if (empty($action)) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                            continue 2;
                        }
                    } else {
                        $action = new DowntimeAction();
                    }
                    $action->fill([
                        'character_id' => $character->id,
                        'downtime_id' => $downtime->id,
                        'action_type_id' => $actionData['type'],
                        'research_project_id' => $researchProjectId,
                        'character_skill_id' => $characterSkillId,
                        'notes' => $actionData['notes'] ?? '',
                        'response' => $actionData['response'] ?? '',
                    ]);
                    $action->save();
                    break;
                case ActionType::ACTION_MISSION:
                    $mission = DowntimeMission::find($actionData['mission_id'] ?? 0);
                    if (empty($mission)) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Mission not found.', ['type' => $type, 'index' => $key]);
                        continue 2;
                    }
                    if (!empty($actionData['notes']) && strlen($actionData['notes']) > 2000) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Notes are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                        continue 2;
                    }
                    if (!empty($actionData['response']) && strlen($actionData['response']) > 4000) {
                        $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Responses are limited to 2000 characters.', ['type' => $type, 'index' => $key]);
                        continue 2;
                    }
                    break;
                case 0:
                    if (!empty($actionData['id'])) {
                        $action = DowntimeAction::find($actionData['id']);
                        if (empty($action)) {
                            $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Action not found.', ['type' => $type, 'index' => $key]);
                        } else {
                            $action->delete();
                        }
                    }
                    break;
                default:
                    $errors[$this->getErrorKey($type, $key)] = __(':type Action :index: Invalid action type.', ['type' => $type, 'index' => $key]);
                    break;
            }
        }
    }

    private function getErrorKey(string $type, int $index): string
    {
        return str_replace(' ', '_', strtolower($type) . '_action_' . $index);
    }
}
