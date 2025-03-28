<?php

namespace App\Http\Controllers;

use App\Mail\CharacterApproved;
use App\Mail\CharacterDenied;
use App\Mail\CharacterReady;
use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\CharacterSkill;
use App\Models\LogType;
use App\Models\Skill;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('characters.index', [
            'activeCharacters' => auth()->user()->characters->whereIn('status_id', [Status::NEW, Status::READY, Status::APPROVED, Status::PLAYED])->sortBy([['primary_secondary', 'desc'], ['name']]),
            'inactiveCharacters' => auth()->user()->characters->whereIn('status_id', [Status::DEAD, Status::RETIRED])->sortBy('name'),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Character::class)) {
            return redirect(route('characters.index'));
        }
        return view('characters.edit');
    }

    public function view(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'));
        }
        return view('characters.view', ['character' => $character]);
    }

    public function print(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'));
        }
        return view('characters.print', ['characters' => [$character]]);
    }

    public function printSkills(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('view', $character)) {
            return redirect(route('characters.index'));
        }
        return view('characters.print-skills', ['characters' => [$character]]);
    }

    public function edit(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
            }
        }
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
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
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('plotco.characters'));
            }
        }
        $errors = [];
        if (count($character->trainingSkills) > 1) {
            $errors[] = __('Character may not have more than one skill in training at character creation.');
        }
        $usedMonths = 0;
        $logs = [];
        foreach ($character->trainedSkills->sortBy('name') as $skill) {
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
        $remainingMonths = $character->background->months - $usedMonths;
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        } else {
            foreach ($character->trainingSkills as $skill) {
                if ($skill->skill->specialties != $skill->skillSpecialties->count()) {
                    $errors[] = __('Character must select specialty for :name.', ['name' => $skill->skill->name]);
                }
                $remainingMonths = $character->background->months - $usedMonths;
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
                if ($character->background->months - $usedMonths == $skill->cost) {
                    $skill->completed = true;
                    $skill->save();
                }
            }
        }
        $remainingMonths = $character->background->months - $usedMonths;
        if ($remainingMonths > 0) {
            $errors[] = __('Character must use all of their background training months.');
        }
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        }
        if ($errors) {
            throw ValidationException::withMessages($errors);
        }
        foreach ($logs as $log) {
            $log->save();
        }
        $character->status_id = Status::APPROVED;
        if ($character->user->characters->where('primary_secondary', true)->count() == 0) {
            $character->primary_secondary = true;
        }
        $character->save();

        $notes = $request->post('notes', '');
        Mail::to($character->user->email)->send(new CharacterApproved($character, $notes));

        return redirect(route('plotco.characters'));
    }

    /**
     * @throws ValidationException
     */
    public function deny(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('approve', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('plotco.characters'));
            }
        }
        $character->status_id = Status::NEW;
        $character->save();

        $notes = $request->post('notes', '');
        Mail::to($character->user->email)->send(new CharacterDenied($character, $notes));

        return redirect(route('characters.index'));
    }

    /**
     * @throws ValidationException
     */
    public function primary(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
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

        return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
    }

    /**
     * @throws ValidationException
     */
    public function ready(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
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
        $remainingMonths = $character->background->months - $usedMonths;
        if ($remainingMonths < 0) {
            $errors[] = __('Character must not exceed their background training months.');
        } else {
            foreach ($character->trainingSkills as $skill) {
                if ($skill->skill->specialties != $skill->skillSpecialties->count()) {
                    $errors[] = __('Character must select specialty for :name.', ['name' => $skill->skill->name]);
                }
                $remainingMonths = $character->background->months - $usedMonths;
                if ($remainingMonths > $skill->cost) {
                    $errors[] = __('Character must use all of their background training months.');
                }
                $usedMonths += $remainingMonths;
                if ($character->background->months - $usedMonths == $skill->cost) {
                    $skill->completed = true;
                    $skill->save();
                }
            }
        }
        $remainingMonths = $character->background->months - $usedMonths;
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

        return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
    }

    public function delete(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('delete', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
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
        return redirect(route('characters.index'));
    }

    public function retire(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
            }
        }
        $character->status_id = Status::RETIRED;
        $character->save();
        return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
    }

    public function kill(Request $request, $characterId)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
            }
        }
        $character->status_id = Status::DEAD;
        $character->save();
        return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
    }

    public function editSkills(Request $request, $characterId, $skillId = null)
    {
        $character = Character::find($characterId);
        if ($request->user()->cannot('edit', $character)) {
            if ($character) {
                return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
            } else {
                return redirect(route('characters.index'));
            }
        }
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
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
            'character_links'=> 'sometimes|string|max:65535|nullable',
            'plot_notes' => 'sometimes|string|max:65535|nullable',
            'events' => 'sometimes|array|exists:events,id',
            'hero_scoundrel' => 'sometimes|int',
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
        $validatedData['short_name'] = $validatedData['short_name'] ?? '';
        $validatedData['history'] = $validatedData['history'] ?? '';
        $validatedData['character_links'] = $validatedData['character_links'] ?? '';
        $validatedData['plot_notes'] = $validatedData['plot_notes'] ?? '';
        $validatedData['former_rank'] = $validatedData['former_rank'] ?? '';
        $validatedData['rank'] = $validatedData['rank'] ?? '';
        $character->fill($validatedData);
        $character->save();

        if (!empty($validatedData['events'])) {
            $character->events()->sync($validatedData['events']);
        }
        return redirect(route('characters.view-pretty', ['characterId' => $character, 'characterName' => Str::slug($character->name)]));
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
            return redirect(route('characters.view', ['characterId' => $validatedData['character_id']]));
        }

        if (!empty($validatedData['id'])) {
            $characterSkill = CharacterSkill::find($validatedData['id']);
            if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
                throw ValidationException::withMessages([__('Character can no longer be modified.')]);
            }
        } else {
            $existing = CharacterSkill::where('character_id', $validatedData['character_id'])
                ->where('skill_id', $validatedData['skill_id'])
                ->count();
            if ($existing) {
                $skill = Skill::find($validatedData['skill_id']);
                if (!$skill->repeatable || $skill->repeatable <= $existing) {
                    throw ValidationException::withMessages([__('Skill has already been taken the maximum number of times.')]);
                }
            }
            $characterSkill = new CharacterSkill();
        }
        $validatedData['completed'] = $validatedData['completed'] ?? false;
        $characterSkill->fill($validatedData);
        $characterSkill->save();

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

        return redirect(route('characters.edit-skills', ['characterId' => $characterSkill->character->id]));
    }

    /**
     * @throws ValidationException
     */
    public function removeSkill(Request $request, $characterId, $skillId)
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
}
