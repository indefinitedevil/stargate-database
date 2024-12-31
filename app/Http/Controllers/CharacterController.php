<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\CharacterLog;
use App\Models\CharacterSkill;
use App\Models\LogType;
use App\Models\Skill;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CharacterController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Character::class)) {
            return redirect(route('dashboard'));
        }
        return view('characters.index', [
            'activeCharacters' => auth()->user()->characters->whereIn('status_id', [Status::NEW, Status::APPROVED, Status::PLAYED])->sortBy('name'),
            'inactiveCharacters' => auth()->user()->characters->whereIn('status_id', [Status::DEAD, Status::RETIRED])->sortBy('name'),
        ]);
    }
    public function all(Request $request)
    {
        if ($request->user()->cannot('viewAll', Character::class)) {
            return redirect(route('characters.index'));
        }
        return view('characters.all', [
            'newCharacters' => Character::all()->where('status_id', Status::NEW)->sortBy('name'),
            'activeCharacters' => Character::all()->whereIn('status_id', [Status::APPROVED, Status::PLAYED])->sortBy('name'),
            'inactiveCharacters' => Character::all()->whereIn('status_id', [Status::DEAD, Status::RETIRED])->sortBy('name'),
        ]);
    }

    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Character::class)) {
            return redirect(route('characters.index'));
        }
        return view('characters.create');
    }

    public function view(Request $request, $characterId)
    {
        if ($request->user()->cannot('view', Character::find($characterId))) {
            return redirect(route('characters.index'));
        }
        return view('characters.view', ['character' => Character::find($characterId)]);
    }

    public function edit(Request $request, $characterId)
    {
        if ($request->user()->cannot('edit', Character::find($characterId))) {
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $character = Character::find($characterId);
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect(route('characters.view', ['characterId' => $character->id]));
        }
        return view('characters.edit', ['character' => $character]);
    }

    public function approve(Request $request, $characterId)
    {
        if ($request->user()->cannot('approve', Character::find($characterId))) {
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $character = Character::find($characterId);
        if (count($character->trainingSkills) > 1) {
            throw ValidationException::withMessages(['Character may not have more than one skill in training at character creation.']);
        }
        $usedMonths = 0;
        foreach ($character->trainedSkills->sortBy('name') as $skill) {
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
            $log->save();
            $usedMonths += $skill->cost;
        }
        foreach ($character->trainingSkills as $skill) {
            $log = new CharacterLog();
            $logData = [
                'character_id' => $character->id,
                'character_skill_id' => $skill->id,
                'locked' => true,
                'amount_trained' => $character->background->months - $usedMonths,
                'log_type_id' => LogType::CHARACTER_CREATION,
                'teacher_id' => null,
            ];
            $log->fill($logData);
            $log->save();
            if ($character->background->months - $usedMonths == $skill->cost) {
                $skill->completed = true;
                $skill->save();
            }
        }
        $character->status_id = Status::APPROVED;
        $character->save();

        return redirect(route('characters.index'));
    }

    public function delete(Request $request, $characterId)
    {
        if ($request->user()->cannot('delete', Character::find($characterId))) {
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $characterLogs = CharacterLog::where('character_id', $characterId)->get();
        foreach ($characterLogs as $log) {
            $log->delete();
        }
        $character = Character::find($characterId);
        $character->delete();
        return redirect(route('characters.index'));
    }

    public function retire(Request $request, $characterId)
    {
        if ($request->user()->cannot('edit', Character::find($characterId))) {
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $character = Character::find($characterId);
        $character->status_id = Status::RETIRED;
        $character->save();
        return redirect(route('characters.view', ['characterId' => $characterId]));
    }

    public function kill(Request $request, $characterId)
    {
        if ($request->user()->cannot('edit', Character::find($characterId))) {
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $character = Character::find($characterId);
        $character->status_id = Status::DEAD;
        $character->save();
        return redirect(route('characters.view', ['characterId' => $characterId]));
    }

    public function editSkills(Request $request, $characterId, $skillId = null)
    {
        if ($request->user()->cannot('edit', Character::find($characterId))) {
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $character = Character::find($characterId);
        if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
            return redirect(route('characters.view', ['characterId' => $character->id]));
        }
        return view('characters.edit-skills', [
            'character' => $character,
            'editSkill' => $skillId ? CharacterSkill::find($skillId) : null,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'integer|exists:characters,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'rank' => 'sometimes|string|max:64|nullable',
            'former_rank' => 'sometimes|string|max:64|nullable',
            'background_id' => 'required|exists:backgrounds,id',
            'status_id' => 'required|exists:statuses,id',
            'history' => 'sometimes|string|nullable',
            'plot_notes' => 'sometimes|string|nullable',
        ]);

        if ($request->user()->cannot('create', Character::class)) {
            return redirect(route('characters.index'));
        }
        if ($request->user()->cannot('edit', Character::find($validatedData['id']))) {
            return redirect(route('characters.view', ['characterId' => $validatedData['id']]));
        }

        if (!empty($validatedData['id'])) {
            $character = Character::find($validatedData['id']);
            if (in_array($character->status_id, [Status::DEAD, Status::RETIRED])) {
                throw ValidationException::withMessages(['Character can no longer be modified.']);
            }
        } else {
            $character = new Character();
        }
        $validatedData['history'] = $validatedData['history'] ?? '';
        $validatedData['plot_notes'] = $validatedData['plot_notes'] ?? '';
        $validatedData['former_rank'] = $validatedData['former_rank'] ?? '';
        $validatedData['rank'] = $validatedData['rank'] ?? '';
        $character->fill($validatedData);
        $character->save();
        return redirect(route('characters.view', ['characterId' => $character->id]));
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
            'completed' => 'boolean',
            'discount_used' => 'boolean',
            'discount_used_by' => 'integer|exists:character_skills,id',
        ]);

        if ($request->user()->cannot('edit', Character::find($validatedData['character_id']))) {
            return redirect(route('characters.view', ['characterId' => $validatedData['character_id']]));
        }

        $newlyCompleted = false;
        if (!empty($validatedData['id'])) {
            $characterSkill = CharacterSkill::find($validatedData['id']);
            if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
                throw ValidationException::withMessages(['Character can no longer be modified.']);
            }
        } else {
            $existing = CharacterSkill::where('character_id', $validatedData['character_id'])
                ->where('skill_id', $validatedData['skill_id'])
                ->count();
            if ($existing) {
                $skill = Skill::find($validatedData['skill_id']);
                if (!$skill->repeatable || $skill->repeatable <= $existing) {
                    throw ValidationException::withMessages(['Skill has already been taken the maximum number of times.']);
                }
            }
            $characterSkill = new CharacterSkill();
        }
        $validatedData['completed'] = $validatedData['completed'] ?? false;
        if (!$characterSkill->completed && $validatedData['completed']) {
            $newlyCompleted = true;
        }
        $characterSkill->fill($validatedData);
        $characterSkill->save();

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

        if (Status::NEW == $characterSkill->character->status_id) {
            if ($newlyCompleted) {
                $log = new CharacterLog();
                $logData = [
                    'character_id' => $characterSkill->character_id,
                    'character_skill_id' => $characterSkill->id,
                    'locked' => false,
                    'amount_trained' => $characterSkill->cost,
                    'log_type_id' => LogType::CHARACTER_CREATION,
                    'teacher_id' => null,
                ];
                $log->fill($logData);
                $log->save();
            } else {
                $log = CharacterLog::where('character_id', $characterSkill->character_id)
                    ->where('character_skill_id', $characterSkill->id)
                    ->where('log_type_id', LogType::CHARACTER_CREATION)
                    ->first();
                if ($log) {
                    $log->delete();
                }
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
            return redirect(route('characters.view', ['characterId' => $characterId]));
        }
        $characterSkill = CharacterSkill::find($skillId);
        if (in_array($characterSkill->character->status_id, [Status::DEAD, Status::RETIRED])) {
            throw ValidationException::withMessages(['Character can no longer be modified.']);
        }
        $characterSkill->delete();

        return redirect(route('characters.edit-skills', ['characterId' => $characterSkill->character->id]));
    }
}
