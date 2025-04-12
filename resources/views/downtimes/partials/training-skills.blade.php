@if ($character->trainingSkills->count())
    <option value="">{{ __('Select a skill to train') }}</option>
    @foreach ($character->trainingSkills as $skill)
        <option value="{{$skill->id}}"
                @if (!empty($action->character_skill_id) && $skill->id == $action->character_skill_id) selected @endif
        >
            {{ __(':name (:trained/:cost months)', ['name' => $skill->name, 'trained' => $skill->trained, 'cost' => $skill->cost]) }}
        </option>
    @endforeach
@else
    <option value="" disabled>{{ __('Start learning a skill first') }}</option>
@endif
