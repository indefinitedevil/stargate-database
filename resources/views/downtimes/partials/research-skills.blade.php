@if ($character->trainedSkills->count())
    <option value="">{{ __('Select a skill for your research') }}</option>
    @foreach ($character->trainedSkills as $skill)
        <option value="{{$skill->id}}"
                @if (!empty($action->character_skill_id) && $skill->id == $action->character_skill_id) selected @endif
        >
            {{ $skill->name }}
        </option>
    @endforeach
@else
    <option value="" disabled>{{ __('You must learn skills before researching') }}</option>
@endif
