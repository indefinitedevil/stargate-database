<?php

namespace App\Http\Controllers;

use App\Models\ActionType;
use App\Models\Character;
use App\Models\CharacterSkill;
use App\Models\Downtime;
use App\Models\DowntimeAction;
use App\Models\DowntimeMission;
use App\Models\ResearchProject;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        if (empty($downtime)) {
            return redirect(route('downtimes.index'));
        }
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

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
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
            $this->validateActions($otherActions ?? [], $errors, $character, $downtime, 'Other');
        }
        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
        return redirect(route('downtimes.submit', [
            'downtimeId' => $downtime->id,
            'characterId' => $character->id,
        ]));
    }

    protected function validateActions(array $actions, array &$errors, Character $character, Downtime $downtime, string $type)
    {
        foreach ($actions as $key => $actionData) {
            switch ($actionData['type']) {
                case ActionType::TRAINING:
                case ActionType::TEACHING:
                case ActionType::UPKEEP:
                case ActionType::UPKEEP_2:
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
                    break;
                case ActionType::OTHER:
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
                        'notes' => $actionData['notes'] ?? '',
                    ]);
                    $action->save();
                    break;
                case ActionType::RESEARCHING:
                    $researchProject = ResearchProject::find($actionData['research_project_id'] ?? 0);
                    if (empty($researchProject)) {
                        $errors[] = __(':type Action :index: Research Project not found.', ['type' => $type, 'index' => $key]);
                    }
                    break;
                case ActionType::MISSION:
                    $mission = DowntimeMission::find($actionData['mission_id'] ?? 0);
                    if (empty($mission)) {
                        $errors[] = __(':type Action :index: Mission not found.', ['type' => $type, 'index' => $key]);
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
