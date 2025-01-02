@php
    use App\Models\Status;
    use Illuminate\Support\Str;
    $fieldClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full';
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Edit character skills') }}</x-slot>
    <x-slot name="header">
        @include('characters.partials.actions', ['character' => $character])
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ sprintf(__('Edit character skills: %s'), $character->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                @include('characters.partials.details', ['character' => $character])
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="grid grid-cols-4 clear-both">
                    <div class="mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Background') }}</h3>
                        <ul>
                            @foreach ($character->background->skills as $skill)
                                <li>{{ $skill->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Trained') }}</h3>
                        <ul>
                            @foreach ($character->trainedSkills->sortBy('name') as $characterSkill)
                                <li>{{ $characterSkill->name }}
                                    ({{ $characterSkill->trained }}/{{ $characterSkill->cost }})
                                    @if ($characterSkill->locked)
                                        <i class="fa-solid fa-lock" title="Expenditure is locked"></i>
                                    @elseif ($characterSkill->discount_used)
                                        <i class="fa-solid fa-user-lock"
                                           title="{{ sprintf('Discounting %s', $characterSkill->discountUsedBy->skill->name) }}"></i>
                                    @elseif ($characterSkill->required)
                                        <i class="fa-solid fa-user-lock"
                                           title="{{ sprintf('Required by %s', $characterSkill->requiredBy) }}"></i>
                                    @else
                                        <a href="{{ route('characters.edit-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"><i
                                                class="fa-solid fa-pencil" title="Edit skill"></i></a>
                                        <a href="{{ route('characters.remove-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"><i
                                                class="fa-solid fa-trash" title="Remove skill"></i></a>
                                    @endif
                                    @if($characterSkill->skill->specialties > 1)
                                        <ul class="list-disc list-inside">
                                            @foreach ($characterSkill->specialties as $specialty)
                                                <li>{{ $specialty->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        @if (Status::NEW == $character->status_id)
                            <p class="mt-1">Total training: {{ $character->completedTrainingMonths }}
                                / {{ $character->background->months }}</p>
                        @endif
                    </div>
                    @if ($character->trainingSkills->count())
                        <div class="mt-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Training') }}</h3>
                            <ul>
                                @foreach ($character->trainingSkills->sortBy('name') as $characterSkill)
                                    <li>
                                        {{ $characterSkill->name }}
                                        ({{ $characterSkill->trained }}/{{ $characterSkill->cost }})
                                        <a href="{{ route('characters.edit-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"><i
                                                class="fa-solid fa-pencil" title="Edit skill"></i></a>
                                        @if ($characterSkill->locked)
                                            <i class="fa-solid fa-lock" title="Expenditure is locked"></i>
                                        @else
                                            <a href="{{ route('characters.remove-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"><i
                                                    class="fa-solid fa-trash" title="Remove skill"></i></a>
                                        @endif
                                        @if($characterSkill->skill->specialties > 1)
                                            <ul>
                                                @foreach ($characterSkill->skillSpecialties as $specialty)
                                                    <li>{{ $specialty->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Feats') }}</h3>
                        <ul>
                            @foreach ($character->feats as $feat)
                                <li>
                                    {{ $feat->name }}
                                    @if ($feat->per_event)
                                        ({{ $feat->getPerEvent($character) }})
                                    @endif
                                    <i class="fa-regular fa-circle-question" title="{{ $feat->description }}"
                                       data-tooltip-target="feat-{{ $feat->id }}"
                                    ></i>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store-skill') }}">
                        @csrf
                        @include('partials.errors')
                        @if (!empty($editSkill))
                            <input type="hidden" name="id" value="{{ $editSkill->id }}">
                        @endif
                        @php $skills = []; @endphp
                        <input type="hidden" name="character_id" value="{{$character->id }}">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div class="">
                                    <label for="skill">{{ __('Skill') }}</label>
                                    @if ($editSkill && $editSkill->completed)
                                        @php
                                            $skills[] = $editSkill->skill;
                                        @endphp
                                        <input type="hidden" name="skill_id" value="{{$editSkill->skill_id }}">
                                        <input type="text" class="{{ $fieldClass }}"
                                               value="{{ $editSkill->name }}" disabled>
                                    @else
                                        <select id="skill" name="skill_id" class="{{ $fieldClass }}" required
                                                onchange="showSkillDescription(this.value)">
                                            >
                                            @if ($editSkill)
                                                @php
                                                    $skills[] = $editSkill->skill;
                                                @endphp
                                                <option value="{{ $editSkill->skill_id }}" selected="selected">
                                                    {{ $editSkill->name }}
                                                    ({{ $editSkill->cost }} months)
                                                </option>
                                            @else
                                                <option value="">{{ __('Select a skill') }}</option>
                                            @endif
                                            @foreach($character->availableSkills as $skill)
                                                @php
                                                    $skills[] = $skill;
                                                @endphp
                                                <option value="{{ $skill->id }}">
                                                    {{ sprintf(__('%s (%s months)'), $skill->name, $skill->cost($character)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>

                                @if ($editSkill && $editSkill->skill->specialties > 0)
                                    <div>
                                        <label for="specialty">{{ __('Specialty') }} ({{ $editSkill->skill->specialties }})</label>
                                        <select id="specialty" name="specialty_id[]" class="{{ $fieldClass }}"
                                                @if ($editSkill->skill->specialties > 1) multiple @endif>
                                            >
                                            @foreach($editSkill->skill->specialtyType->skillSpecialties->sortBy('name') as $specialty)
                                                <option value="{{ $specialty->id }}"
                                                        @if($editSkill->skillSpecialties->contains($specialty)) selected @endif
                                                >
                                                    {{ $specialty->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if ($editSkill && ($editSkill->discountAvailable || $editSkill->discountedBy->count()))
                                    <div>
                                        <label for="discounted_by">{{ __('Discount') }}</label>
                                        <select id="discounted_by" name="discounted_by[]" class="{{ $fieldClass }}"
                                                multiple>
                                            <option value="">{{ __('No discount') }}</option>
                                            @foreach($editSkill->discountedBy as $discountingSkill)
                                                <option value="{{ $discountingSkill->id }}" selected>
                                                    {{ sprintf(__('%s (-%s months)'), $discountingSkill->name, $discountingSkill->discountFor($editSkill->skill_id)) }}
                                                </option>
                                            @endforeach
                                            @foreach($editSkill->discountsAvailable as $discountingSkill)
                                                <option value="{{ $discountingSkill->id }}"
                                                        @if($editSkill->discountedBy->contains(['id' => $discountingSkill->id])) selected @endif
                                                >
                                                    {{ $discountingSkill->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif (!$editSkill)
                                    <div>
                                        <p>{{ __('To apply a discount, first save the skill, then edit the saved skill to select applicable discounts.') }}</p>
                                    </div>
                                @endif

                                <div>
                                    <label for="completed">{{ __('Completed') }}</label>
                                    <input type="checkbox" id="completed" name="completed" class="" value="1"
                                           @if (!empty($editSkill) && $editSkill->completed) checked="checked" @endif>
                                </div>

                                <div class="flex items-center gap-4">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                            <div>
                                @foreach($skills as $skill)
                                    <div id="skill-description-{{ $skill->id }}"
                                         class="skill-description mt-1 @if (!$editSkill || $editSkill->skill->id != $skill->id) hidden @endif">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $skill->name }}</h3>
                                        <div class="space-y-1">
                                            {!! Str::of($skill->description)->markdown()->replace('<ul>', '<ul class="list-disc list-inside">') !!}
                                        </div>
                                        @if ($skill->specialties)
                                            <div class="mt-1">
                                                <p>{{ __('To choose specialties, first save the skill, then edit the saved skill to select applicable specialties.') }}</p>
                                            </div>
                                        @endif
                                        @if ($skill->feats->count())
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Feats') }}</h4>
                                            <ul class="grid grid-cols-2 gap-2">
                                                @foreach($skill->feats as $feat)
                                                    <li>
                                                        {{ $feat->name }}
                                                        <i class="fa-regular fa-circle-question"
                                                           title="{{ $feat->description }}"
                                                           data-tooltip-target="feat-{{ $feat->id }}"
                                                        ></i>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if ($skill->cards->count())
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Cards') }}</h4>
                                            <ul class="grid grid-cols-4 gap-2">
                                                @foreach($skill->cards as $card)
                                                    <li>
                                                        {{ $card->name }}
                                                        ({{ $card->pivot->number }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <script>
                                function showSkillDescription(skillId) {
                                    let skills = document.querySelectorAll('.skill-description');
                                    skills.forEach(function (skill) {
                                        skill.classList.add('hidden');
                                    });
                                    let skill = document.getElementById('skill-description-' + skillId);
                                    skill.classList.remove('hidden');
                                }
                            </script>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
