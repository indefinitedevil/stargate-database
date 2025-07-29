@if ($character->teachingSkills->count())
    <option value="">{{ __('Select a skill to teach') }}</option>
    @foreach ($character->teachingSkills as $skill)
        <option value="{{$skill->id}}"
                @if (!empty($action->character_skill_id) && $skill->id == $action->character_skill_id) selected @endif
        >
            {{ $skill->name }}
        </option>
    @endforeach
@else
    <option value="" disabled>{{ __('You must learn skills before you can teach them') }}</option>
@endif
