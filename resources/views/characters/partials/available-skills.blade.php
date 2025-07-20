@php
    global $skills;
@endphp
@foreach($character->availableSkills as $skill)
    @if (empty($currentCategory) || $currentCategory != $skill->skill_category_id)
        @if (!empty($currentCategory))
            {!! '</optgroup>' !!}
        @endif
        @php $currentCategory = $skill->skill_category_id; @endphp
        {!! '<optgroup label="' . __(':name Skills', ['name' => $skill->skillCategory->name]) . '">' !!}
    @endif
    @php
        $skills[$skill->id] = $skill;
        if ($skill->repeatable) {
            $count = $character->skills->where('skill_id', $skill->id)->count();
            if ($count >= $skill->repeatable) {
                continue;
            }
        }
    @endphp
    <option value="{{ $skill->id }}">
        {{ __(':name (:cost months)', ['name' => $skill->name, 'cost' => $skill->cost($character)]) }}
    </option>
@endforeach
@if (!empty($currentCategory))
    {!! '</optgroup>' !!}
@endif
