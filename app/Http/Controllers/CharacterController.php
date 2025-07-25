<?php

namespace App\Http\Controllers;

use App\Helpers\PlotHelper;
use App\Mail\CharacterApproved;
use App\Mail\CharacterDenied;
use App\Mail\CharacterReady;
use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\CharacterSkill;
use App\Models\CharacterTrait;
use App\Models\Event;
use App\Models\LogType;
use App\Models\Skill;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Character::class)) {
            return redirect(route('dashboard'))
                ->with('errors', new MessageBag([__('Characters cannot be viewed.')]));
        }
        return view('characters.index', [
            'activeCharacters' => auth()->user()->characters->whereIn('status_id', [Status::NEW, Status::READY, Status::APPROVED, Status::PLAYED])->sortBy([['primary_secondary', 'desc'], ['name']]),
            'inactiveCharacters' => auth()->user()->characters->whereIn('status_id', [Status::DEAD, Status::RETIRED])->sortBy('name'),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Character::class)) {
            return redirect(route('characters.index'))
                ->with('errors', new MessageBag([__('Characters cannot be created.')]));
        }
        return view('characters.edit');
    }

    public function view(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'))
                ->with('errors', new MessageBag([__('Character not found.')]));
        }
        return view('characters.view', ['character' => $character]);
    }

    public function logs(Request $request, $characterId, $logId = null)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'))
                ->with('errors', new MessageBag([__('Character not found.')]));
        }
        if ($character->status_id < Status::APPROVED) {
            return redirect($character->getViewRoute())
                ->with('errors', new MessageBag([__('Character must be approved to view logs.')]));
        }
        $logs = $character->logs()->with(['characterSkill.skill', 'logType']);
        if (auth()->user()->cannot('view hidden notes')) {
            $logs->where(function ($query) {
                $query->where('notes', '!=', '')
                    ->orWhere('log_type_id', '!=', LogType::PLOT);
            });
        }
        return view('characters.logs', [
            'character' => $character,
            'logs' => $logs->orderBy('created_at', 'desc')->paginate(20),
            'editLog' => $logId ? CharacterLog::find($logId) : null,
        ]);
    }

    public function print(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'))
                ->with('errors', new MessageBag([__('Character not found.')]));
        }
        return view('characters.print', ['characters' => [$character]]);
    }

    public function printSkills(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'))
                ->with('errors', new MessageBag([__('Character not found.')]));
        }
        return view('characters.print-skills', ['characters' => [$character]]);
    }

    public function edit(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot edit :character.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect($character->getViewRoute());
        }
        return view('characters.edit', ['character' => $character]);
    }

    /**
     * @throws ValidationException
     */
    public function approve(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('approve', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot mark :character as approved.', ['character' => $character->listName])]));
            } else {
                return redirect(route('plotco.characters'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        $errors = [];
        if (count($character->trainingSkills) > 1) {
            $errors[] = __('Character may not have more than one skill in training at character creation.');
        }
        $usedMonths = 0;
        $logs = [];
        foreach ($character->trainedSkills->sortBy('name') as $skill) {
            if (!CharacterLog::where('character_skill_id', $skill->id)->where('log_type_id', LogType::PLOT)->exists()) {
                if ($skill->skill->specialties != $skill->skillSpecialties->count()) {
                    $errors[] = __('Character must select specialty for :name.', ['name' => $skill->skill->name]);
                }
                $log = new CharacterLog();
                $logData = [
                    'character_id' => $character->id,
                    'character_skill_id' => $skill->id,
                    'locked' => true,
                    'amount_trained' => $skill->cost,
                    'log_type_id' => LogType::CHARACTER_CREATION,
                    'teacher_id' => null,
                ];
                $log->fill($logData);
                $logs[] = $log;
                $usedMonths += $skill->cost;
            }
        }
        $remainingMonths = $character->background->adjustedMonths - $usedMonths;
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        } else {
            foreach ($character->trainingSkills as $skill) {
                if ($skill->skill->specialties != $skill->skillSpecialties->count()) {
                    $errors[] = __('Character must select specialty for :name.', ['name' => $skill->skill->name]);
                }
                $remainingMonths = $character->background->adjustedMonths - $usedMonths;
                if ($remainingMonths > $skill->cost) {
                    $errors[] = __('Character must use all of their background training months.');
                }
                $log = new CharacterLog();
                $logData = [
                    'character_id' => $character->id,
                    'character_skill_id' => $skill->id,
                    'locked' => true,
                    'amount_trained' => $remainingMonths,
                    'log_type_id' => LogType::CHARACTER_CREATION,
                    'teacher_id' => null,
                ];
                $log->fill($logData);
                $logs[] = $log;
                $usedMonths += $remainingMonths;
                if ($character->background->adjustedMonths - $usedMonths == $skill->cost) {
                    $skill->completed = true;
                    $skill->save();
                }
            }
        }
        $remainingMonths = $character->background->adjustedMonths - $usedMonths;
        if ($remainingMonths > 0) {
            $errors[] = __('Character must use all of their background training months.');
        }
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        foreach ($character->background->skills as $skill) {
            $characterSkill = new CharacterSkill();
            $characterSkill->character_id = $character->id;
            $characterSkill->skill_id = $skill->id;
            $characterSkill->completed = true;
            $characterSkill->save();

            $log = new CharacterLog();
            $logData = [
                'character_id' => $character->id,
                'character_skill_id' => $characterSkill->id,
                'locked' => true,
                'amount_trained' => 0,
                'log_type_id' => LogType::CHARACTER_CREATION,
                'teacher_id' => null,
            ];
            $log->fill($logData);
            $logs[] = $log;
        }

        foreach ($logs as $log) {
            $log->save();
        }
        $character->status_id = Status::APPROVED;
        if ($character->user->characters->where('primary_secondary', true)->count() == 0) {
            $character->primary_secondary = true;
        }
        $character->save();

        \App\Events\CharacterApproved::dispatch($character);

        $notes = $request->post('notes', '');
        Mail::to($character->user->email)->send(new CharacterApproved($character, $notes));

        return redirect(route('plotco.characters'))
            ->with('success', new MessageBag([__('Character :character approved.', ['character' => $character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function deny(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('approve', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot mark :character as denied.', ['character' => $character->listName])]));
            } else {
                return redirect(route('plotco.characters'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        $character->status_id = Status::NEW;
        $character->save();

        $notes = $request->post('notes', '');
        Mail::to($character->user->email)->send(new CharacterDenied($character, $notes));

        return redirect(route('characters.index'))
            ->with('success', new MessageBag([__('Character :character denied.', ['character' => $character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function primary(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot mark :character as primary.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        $secondaryCharacters = Character::where('user_id', $character->user_id)
            ->where('primary_secondary', true)
            ->where('id', '!=', $characterId)
            ->get();
        foreach ($secondaryCharacters as $secondaryCharacter) {
            $secondaryCharacter->primary_secondary = false;
            $secondaryCharacter->save();
        }
        $character->primary_secondary = true;
        $character->save();

        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character marked as primary.', ['character' => $character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function ready(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot mark :character as ready.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        $errors = [];
        if (count($character->trainingSkills) > 1) {
            $errors[] = __('Character may not have more than one skill in training at character creation.');
        }
        $usedMonths = 0;
        foreach ($character->trainedSkills->sortBy('name') as $skill) {
            if ($skill->skill->specialties != $skill->skillSpecialties->count()) {
                $errors[] = __('Character must select specialty for :name.', ['name' => $skill->skill->name]);
            }
            $usedMonths += $skill->cost;
        }
        $remainingMonths = $character->background->adjustedMonths - $usedMonths;
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        } else {
            foreach ($character->trainingSkills as $skill) {
                if ($skill->skill->specialties != $skill->skillSpecialties->count()) {
                    $errors[] = __('Character must select specialty for :name.', ['name' => $skill->skill->name]);
                }
                $remainingMonths = $character->background->adjustedMonths - $usedMonths;
                if ($remainingMonths > $skill->cost) {
                    $errors[] = __('Character must use all of their background training months.');
                }
                $usedMonths += $remainingMonths;
                if ($character->background->adjustedMonths - $usedMonths == $skill->cost) {
                    $skill->completed = true;
                    $skill->save();
                }
            }
        }
        $remainingMonths = $character->background->adjustedMonths - $usedMonths;
        if ($remainingMonths > 0) {
            $errors[] = __('Character must use all of their background training months.');
        }
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        }
        if ($errors) {
            throw ValidationException::withMessages(array_unique($errors));
        }
        $character->status_id = Status::READY;
        $character->save();

        Mail::to(config('mail.plot_coordinator.address'))->send(new CharacterReady($character));

        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character marked as ready.', ['character' => $character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function reset(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot reset :character.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }

        if (!$character->canBeReset()) {
            throw ValidationException::withMessages([__('Character cannot be reset.')]);
        }

        $character->reset();

        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character reset to pre-approval state.', ['character' => $character->listName])]));
    }

    public function delete(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('delete', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('errors', new MessageBag([__('You cannot mark :character as deleted.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        $characterLogs = CharacterLog::where('character_id', $characterId)->get();
        foreach ($characterLogs as $log) {
            $log->delete();
        }
        $characterSkills = CharacterSkill::where('character_id', $characterId)->get();
        foreach ($characterSkills as $skill) {
            $skill->delete();
        }
        $character->events()->sync([]);
        $character->delete();
        return redirect(route('characters.index'))
            ->with('success', new MessageBag([__('Character :character deleted.', ['character' => $character->listName])]));
    }

    public function retire(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('success', new MessageBag([__('You cannot mark :character as retired.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('errors', new MessageBag([__('Character not found.')]));
            }
        }
        $character->status_id = Status::RETIRED;
        $character->save();
        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character marked as retired.', ['character' => $character->listName])]));
    }

    public function kill(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('success', new MessageBag([__('You cannot mark :character as deceased.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('success', new MessageBag([__('Character not found.')]));
            }
        }
        $character->status_id = Status::DEAD;
        $character->save();
        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character marked as deceased.', ['character' => $character->listName])]));
    }

    public function inactive(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('inactive', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('success', new MessageBag([__('You cannot mark :character as inactive.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('success', new MessageBag([__('Character not found.')]));
            }
        }
        $character->status_id = Status::INACTIVE;
        $character->save();
        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character marked as inactive.', ['character' => $character->listName])]));
    }

    public function played(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('played', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('success', new MessageBag([__('You cannot be mark :character as played.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('success', new MessageBag([__('Character not found.')]));
            }
        }
        $character->status_id = Status::PLAYED;
        $character->save();
        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character marked as played.', ['character' => $character->listName])]));
    }

    public function resuscitate(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('resuscitate', $character)) {
            if ($character) {
                return redirect($character->getViewRoute())
                    ->with('success', new MessageBag([__('You cannot resuscitate :character.', ['character' => $character->listName])]));
            } else {
                return redirect(route('characters.index'))
                    ->with('success', new MessageBag([__('Character not found.')]));
            }
        }
        $characterSkill = new CharacterSkill();
        $characterSkill->fill([
            'character_id' => $character->id,
            'skill_id' => PlotHelper::SKILL_RESUSCITATION,
            'completed' => true,
        ]);
        $characterSkill->save();
        $log = new CharacterLog();
        $log->fill([
            'character_id' => $character->id,
            'character_skill_id' => $characterSkill->id,
            'locked' => true,
            'amount_trained' => 0,
            'log_type_id' => LogType::PLOT,
            'teacher_id' => null,
        ]);
        $log->save();
        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character resuscitated.', ['character' => $character->listName])]));
    }

    public function editSkills(Request $request, $characterId, $skillId = null)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect($character->getViewRoute());
            } else {
                return redirect(route('characters.index'));
            }
        }
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect($character->getViewRoute());
        }
        return view('characters.edit-skills', [
            'character' => $character,
            'editSkill' => $skillId ? CharacterSkill::find($skillId) : null,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'sometimes|integer|exists:characters,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:64',
            'short_name' => 'sometimes|string|max:64|nullable',
            'rank' => 'sometimes|string|max:64|nullable',
            'former_rank' => 'sometimes|string|max:64|nullable',
            'background_id' => 'required|exists:backgrounds,id',
            'status_id' => 'required|exists:statuses,id',
            'history' => 'sometimes|string|max:65535|nullable',
            'character_links' => 'sometimes|string|max:65535|nullable',
            'plot_notes' => 'sometimes|string|max:65535|nullable',
            'other_abilities' => 'sometimes|string|max:65535|nullable',
            'events' => 'sometimes|array|exists:events,id',
            'hero_scoundrel' => 'sometimes|int',
            'traits' => 'sometimes|array',
        ]);

        if ($request->user()->cannot('create', Character::class)) {
            return redirect(route('characters.index'));
        }
        if (!empty($validatedData['id']) && $request->user()->cannot('edit', Character::find($validatedData['id']))) {
            return redirect(route('characters.view', ['characterId' => $validatedData['id']]));
        }

        if (!empty($validatedData['id'])) {
            $character = Character::find($validatedData['id']);
            if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
                throw ValidationException::withMessages([__('Character can no longer be modified.')]);
            }
        } else {
            $character = new Character();
        }
        $validatedData['short_name'] = $validatedData['short_name'] ?? null;
        $validatedData['history'] = $validatedData['history'] ?? '';
        $validatedData['character_links'] = $validatedData['character_links'] ?? '';
        $validatedData['plot_notes'] = $validatedData['plot_notes'] ?? '';
        $validatedData['other_abilities'] = $validatedData['other_abilities'] ?? '';
        $validatedData['former_rank'] = $validatedData['former_rank'] ?? '';
        $validatedData['rank'] = $validatedData['rank'] ?? '';
        $character->fill($validatedData);
        $character->save();

        if (!empty($validatedData['traits'])) {
            $traits = CharacterTrait::all();
            foreach ($traits as $trait) {
                if (empty($validatedData['traits'][$trait->id])) {
                    $validatedData['traits'][$trait->id]['status'] = false;
                }
            }
            $oldTraits = [];
            foreach ($character->characterTraits as $trait) {
                if ($trait->pivot->status) {
                    $oldTraits[] = $trait->name;
                }
            }
            $changes = $character->characterTraits()->sync($validatedData['traits']);
            foreach ($changes as $changeType) {
                if (!empty($changeType)) {
                    $character->resetIndicators();
                    break;
                }
            }
            $newTraits = [];
            foreach ($character->characterTraits()->get() as $trait) {
                if ($trait->pivot->status) {
                    $newTraits[] = $trait->name;
                }
            }
            $characterSkill = $character->skills()->where('skill_id', Skill::PLOT_CHANGE)->first();
            if (!$characterSkill) {
                $characterSkill = new CharacterSkill();
                $characterSkill->fill([
                    'character_id' => $character->id,
                    'skill_id' => Skill::PLOT_CHANGE,
                    'completed' => true,
                ]);
                $characterSkill->save();
            }
            $log = new CharacterLog();
            $log->fill([
                'character_id' => $character->id,
                'character_skill_id' => $characterSkill->id,
                'skill_completed' => true,
                'locked' => true,
                'log_type_id' => LogType::PLOT,
                'plot_notes' => __('Traits changed from :old to :new.', [
                    'old' => implode(', ', $oldTraits),
                    'new' => implode(', ', $newTraits),
                ]),
            ]);
            $log->save();
        }

        return redirect($character->getViewRoute())
            ->with('success', new MessageBag([__('Character :character saved.', ['character' => $character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function storeSkill(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'integer|exists:character_skills,id',
            'character_id' => 'integer|exists:characters,id',
            'skill_id' => 'required|exists:skills,id',
            'specialty_id' => 'array|exists:skill_specialties,id',
            'completed' => 'boolean',
            'discount_used' => 'boolean',
            'discount_used_by' => 'integer|exists:character_skills,id',
        ]);

        if (!empty($validatedData['character_id']) && $request->user()->cannot('edit', Character::find($validatedData['character_id']))) {
            return redirect(route('characters.view', ['characterId' => $validatedData['character_id']]))
                ->with('errors', new MessageBag([__('You cannot edit this character.')]));
        }

        if (!empty($validatedData['id'])) {
            $characterSkill = CharacterSkill::find($validatedData['id']);
            if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
                return redirect(route('characters.view', ['characterId' => $validatedData['character_id']]))
                    ->with('errors', new MessageBag([__('You cannot modify this character.')]));
            }
        } else {
            $existing = CharacterSkill::where('character_id', $validatedData['character_id'])
                ->where('skill_id', $validatedData['skill_id'])
                ->count();
            if ($existing) {
                $skill = Skill::find($validatedData['skill_id']);
                if (!$skill->repeatable || $skill->repeatable <= $existing) {
                    throw ValidationException::withMessages([__('Skill has already been taken the maximum number of times.')]);
                } elseif (PlotHelper::SKILL_RESUSCITATION_BUYBACK == $skill->id) {
                    $resuscitations = CharacterSkill::where('character_id', $validatedData['character_id'])
                        ->where('skill_id', PlotHelper::SKILL_RESUSCITATION)
                        ->count();
                    if ($resuscitations <= $existing) {
                        throw ValidationException::withMessages([__('Skill has already been taken the maximum number of times.')]);
                    }
                }
            }
            $characterSkill = new CharacterSkill();
        }
        $validatedData['completed'] = $validatedData['completed'] ?? false;
        $characterSkill->fill($validatedData);
        $characterSkill->save();

        if ($validatedData['completed'] && $characterSkill->character->status_id > Status::READY) {
            $log = new CharacterLog();
            $log->fill([
                'character_id' => $characterSkill->character->id,
                'character_skill_id' => $characterSkill->id,
                'locked' => true,
                'amount_trained' => 0,
                'log_type_id' => LogType::PLOT,
                'teacher_id' => null,
            ]);
            $log->save();
        }

        if (!empty($validatedData['specialty_id'])) {
            if ($characterSkill->skill->specialties != count($validatedData['specialty_id'])) {
                throw ValidationException::withMessages([__('Character must select correct specialty count for :name.', [$characterSkill->skill->name])]);
            }
            $characterSkill->skillSpecialties()->sync($validatedData['specialty_id']);
        }

        if ($request->get('discounted_by', [])) {
            foreach ($characterSkill->discountedBy as $discountedBy) {
                $discountedBy->discount_used = false;
                $discountedBy->discount_used_by = null;
                $discountedBy->save();
            }
            foreach ($request->get('discounted_by') as $discountedBy) {
                $discountingSkill = CharacterSkill::find($discountedBy);
                $discountingSkill->discount_used = true;
                $discountingSkill->discount_used_by = $characterSkill->id;
                $discountingSkill->save();
            }
        }

        return redirect()->back()
            ->with('success', new MessageBag([__('Character :character skill saved.', ['character' => $characterSkill->character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function storeLog(Request $request)
    {
        $validatedData = $request->validate([
            'log_id' => 'integer|exists:character_logs,id',
            'character_id' => 'integer|exists:characters,id',
            'character_skill_id' => 'integer|exists:character_skills,id',
            'skill_id' => 'integer|exists:skills,id',
            'specialty_id' => 'array|exists:skill_specialties,id',
            'completed' => 'boolean',
            'amount_trained' => 'integer',
            'body_change' => 'integer',
            'temp_body_change' => 'integer',
            'vigor_change' => 'integer',
            'temp_vigor_change' => 'integer',
            'notes' => 'string|nullable|max:255',
            'plot_notes' => 'string|nullable|max:255',
        ]);

        if (!empty($validatedData['character_id']) && $request->user()->cannot('edit', Character::find($validatedData['character_id']))) {
            return redirect(route('characters.view', ['characterId' => $validatedData['character_id']]))
                ->with('errors', new MessageBag([__('You cannot edit this character.')]));
        }

        $skill = Skill::find($validatedData['skill_id']);

        if (!empty($validatedData['character_skill_id'])) {
            $characterSkill = CharacterSkill::find($validatedData['character_skill_id']);
            if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
                return redirect(route('characters.view', ['characterId' => $validatedData['character_id']]))
                    ->with('errors', new MessageBag([__('You cannot modify this character.')]));
            }
        } else {
            $existing = CharacterSkill::where('character_id', $validatedData['character_id'])
                ->where('skill_id', $validatedData['skill_id'])
                ->count();
            if ($existing) {
                if (!$skill->repeatable || $skill->repeatable <= $existing) {
                    throw ValidationException::withMessages(['skill_id' => __('Skill has already been taken the maximum number of times.')]);
                } elseif (PlotHelper::SKILL_RESUSCITATION_BUYBACK == $skill->id) {
                    $resuscitations = CharacterSkill::where('character_id', $validatedData['character_id'])
                        ->where('skill_id', PlotHelper::SKILL_RESUSCITATION)
                        ->count();
                    if ($resuscitations <= $existing) {
                        throw ValidationException::withMessages(['skill_id' => __('Skill has already been taken the maximum number of times.')]);
                    }
                }
            }
            $characterSkill = new CharacterSkill();
        }
        $validatedData['completed'] = $validatedData['completed'] ?? false;

        if (!$validatedData['completed'] && !$validatedData['amount_trained'] && $skill->cost() > 0) {
            throw ValidationException::withMessages(['log' => __('Log must apply training or a completed skill.')]);
        }

        $characterSkill->fill([
            'completed' => $validatedData['completed'],
            'character_id' => $validatedData['character_id'],
            'skill_id' => $validatedData['skill_id'],
        ]);
        $characterSkill->save();

        if (!empty($validatedData['log_id'])) {
            $log = CharacterLog::find($validatedData['log_id']);
        } else {
            $log = new CharacterLog();
            $log->fill([
                'log_type_id' => LogType::PLOT,
            ]);
        }
        $log->fill([
            'character_id' => $characterSkill->character->id,
            'character_skill_id' => $characterSkill->id,
            'locked' => true,
            'amount_trained' => $validatedData['amount_trained'] ?? 0,
            'body_change' => $validatedData['body_change'] ?? 0,
            'temp_body_change' => $validatedData['temp_body_change'] ?? 0,
            'vigor_change' => $validatedData['vigor_change'] ?? 0,
            'temp_vigor_change' => $validatedData['temp_vigor_change'] ?? 0,
            'notes' => $validatedData['notes'] ?? NULL,
            'plot_notes' => $validatedData['plot_notes'] ?? NULL,
            'skill_completed' => $validatedData['completed'],
        ]);
        $log->save();

        if (!empty($validatedData['specialty_id'])) {
            if ($characterSkill->skill->specialties != count($validatedData['specialty_id'])) {
                throw ValidationException::withMessages(['specialty_id' => __('You must select correct specialty count for :name.', [$characterSkill->skill->name])]);
            }
            $characterSkill->skillSpecialties()->sync($validatedData['specialty_id']);
        }

        return redirect()->back()
            ->with('success', new MessageBag(['log' => __('Character log for :character saved.', ['character' => $characterSkill->character->listName])]));
    }

    /**
     * @throws ValidationException
     */
    public function deleteSkill(Request $request, $characterId, $skillId)
    {
        if ($request->user()->cannot('edit', Character::find($characterId))) {
            return redirect(route('dashboard'));
        }
        $characterSkill = CharacterSkill::find($skillId);
        if (empty($characterSkill)) {
            throw ValidationException::withMessages([__('Skill not found.')]);
        }
        if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
            throw ValidationException::withMessages([__('Character can no longer be modified.')]);
        }
        if ($characterSkill->locked) {
            throw ValidationException::withMessages([__('Skill cannot be deleted.')]);
        }
        if ($characterSkill->discountedBy->count()) {
            foreach ($characterSkill->discountedBy as $discountedBy) {
                $discountedBy->discount_used = false;
                $discountedBy->discount_used_by = null;
                $discountedBy->save();
            }
        }
        $characterSkill->delete();

        return redirect(route('characters.edit-skills', ['characterId' => $characterSkill->character->id]));
    }

    /**
     * @throws ValidationException
     */
    public function removeSkill(Request $request, $characterId, $skillId)
    {
        if ($request->user()->cannot('edit all characters')) {
            return redirect(route('dashboard'));
        }
        $characterSkill = CharacterSkill::find($skillId);
        if (empty($characterSkill)) {
            throw ValidationException::withMessages([__('Skill not found.')]);
        }
        if ($characterId != $characterSkill->character_id) {
            throw ValidationException::withMessages([__('Character doesn\'t match skill.')]);
        }
        if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
            throw ValidationException::withMessages([__('Character can no longer be modified.')]);
        }
        if ($characterSkill->discountedBy->count()) {
            foreach ($characterSkill->discountedBy as $discountedBy) {
                $discountedBy->discount_used = false;
                $discountedBy->discount_used_by = null;
                $discountedBy->save();
            }
        }
        $characterSkill->removed = true;
        $characterSkill->save();

        $log = new CharacterLog();
        $log->fill([
            'character_id' => $characterSkill->character->id,
            'character_skill_id' => $characterSkill->id,
            'locked' => true,
            'amount_trained' => 0,
            'log_type_id' => LogType::PLOT,
            'notes' => 'Skill removed.',
            'skill_removed' => true,
        ]);
        $log->save();

        return redirect(route('characters.edit-skills', ['characterId' => $characterSkill->character->id]));
    }
}
