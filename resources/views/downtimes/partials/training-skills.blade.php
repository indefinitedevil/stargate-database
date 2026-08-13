@if ($character->trainingSkills->count())
    <option value="">{{ __('Select a skill to train') }}</option>
    @foreach ($character->trainingSkills as $skill)
        <option value="{{$skill->id}}"
                @if (!empty($action->character_skill_id) && $skill->id == $action->character_skill_id) selected @endif
                @if ($skill->skill->requires_teacher && !in_array($skill->skill_id, $downtime->trainingCourseSkillIds)) disabled @endif
        >
            {{ __(':name (:trained/:cost months):teacher', ['name' => $skill->name, 'trained' => $skill->trained, 'cost' => $skill->cost, 'teacher' => ($skill->skill->requires_teacher && !in_array($skill->id, $downtime->trainingCourseSkillIds) ? __(' - requires teacher') : '')]) }}
        </option>
    @endforeach
@else
    <option value="" disabled>{{ __('Start learning a skill first') }}</option>
@endif
