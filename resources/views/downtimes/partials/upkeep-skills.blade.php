@if ($character->requiredUpkeepSkills->count())
    <option value="">{{ __('Select a skill to upkeep') }}</option>
    @foreach ($character->requiredUpkeepSkills as $skill)
        <option value="{{$skill->id}}"
                @if (!empty($action->character_skill_id) && $skill->id == $action->character_skill_id) selected @endif
        >
            {{ $skill->name }}
        </option>
    @endforeach
@else
    <option value="" disabled>{{ __('You have no skills that require upkeep') }}</option>
@endif
