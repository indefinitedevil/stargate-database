@php
    use App\Models\Feat;
    use App\Models\Status;
    use Illuminate\Support\Str;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Edit character skills') }}</x-slot>
    <x-slot name="header">
        @include('characters.partials.actions')
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ sprintf(__('Edit character skills: %s'), $character->name) }}
            @if($character->isPrimary)
                <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @include('plotco.partials.approval')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                @include('characters.partials.details')
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="grid grid-cols-1 sm:grid-cols-4 clear-both">
                    <div class="mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Background') }}</h3>
                        <ul class="space-y-6 sm:space-y-2">
                            @foreach ($character->background->skills as $skill)
                                <li>
                                    <span class="cursor-pointer underline decoration-dashed underline-offset-4"
                                          onclick="toggleVisibility('skill-{{ $skill->id }}')">
                                        {{ $skill->name }}
                                        <i class="fa-regular fa-circle-question cursor-pointer"
                                           title="{{ __('Show description') }}"
                                        ></i>
                                    </span>
                                    <div id="skill-{{ $skill->id }}" class="text-sm hidden pl-4 space-y-2 mb-2">
                                        {!! Str::of($skill->description)->markdown() !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-6 sm:mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Trained') }}</h3>
                        <ul class="space-y-6 sm:space-y-2">
                            @foreach ($character->trainedSkills->sortBy('name') as $characterSkill)
                                <li class="leading-loose sm:leading-normal">
                                    <span class="cursor-pointer underline decoration-dashed underline-offset-4"
                                          onclick="toggleVisibility('skill-{{ $characterSkill->skill_id }}')">
                                        {{ $characterSkill->name }}
                                        @if($characterSkill->skill->feats->contains(Feat::FLASH_OF_INSIGHT))
                                            *
                                            @php $flashOfInsight = true; @endphp
                                        @endif
                                        @if($characterSkill->skill->feats->contains(Feat::BOTCH_JOB))
                                            †
                                            @php $botchJob = true; @endphp
                                        @endif
                                        <i class="fa-regular fa-circle-question cursor-pointer"
                                           title="{{ __('Show description') }}"
                                        ></i>
                                     </span>
                                    ({{ $characterSkill->trained }}/{{ $characterSkill->cost }})
                                    @if ($characterSkill->locked)
                                        <i class="fa-solid fa-lock inline-block ml-4 sm:ml-0"
                                           title="Expenditure is locked"></i>
                                    @elseif ($characterSkill->discount_used)
                                        <i class="fa-solid fa-user-lock inline-block ml-4 sm:ml-0"
                                           title="{{ __('Discounting :skill', ['skill' => $characterSkill->discountUsedBy->skill->name]) }}"></i>
                                        <span class="sm:hidden"> {{ __('Discount used') }}</span>
                                    @elseif ($characterSkill->required)
                                        <i class="fa-solid fa-user-lock inline-block ml-4 sm:ml-0"
                                           title="{{ sprintf('Required by %s', $characterSkill->requiredBy) }}"></i>
                                        <span class="sm:hidden"> {{ __('Required') }}</span>
                                    @else
                                        <a href="{{ route('characters.edit-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"
                                           class="inline-block ml-4 sm:ml-0"
                                        ><i class="fa-solid fa-pencil" title="Edit skill"></i><span
                                                class="sm:hidden"> {{ __('Edit') }}</span></a>
                                        <a href="{{ route('characters.remove-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"
                                           class="inline-block ml-4 sm:ml-0"
                                        ><i class="fa-solid fa-trash" title="Remove skill"></i><span
                                                class="sm:hidden"> {{ __('Remove') }}</span></a>
                                    @endif
                                    @if($characterSkill->skill->specialties > 1)
                                        <ul class="list-disc list-inside">
                                            @foreach ($characterSkill->specialties as $specialty)
                                                <li>{{ $specialty->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <div id="skill-{{ $characterSkill->skill_id }}"
                                         class="text-sm hidden pl-4 space-y-2 mt-1 mb-2">
                                        {!! Str::of($characterSkill->skill->description)->markdown() !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if (in_array($character->status_id, [Status::NEW, Status::READY]))
                            <p class="mt-1">
                                {{ sprintf(__('Total training: %d / %s'), $character->completedTrainingMonths, $character->background->months) }}
                            </p>
                        @endif
                        @if(!empty($flashOfInsight))
                            <p class="mt-1">{{ __('* Flash of Insight discount available') }}</p>
                        @endif
                        @if(!empty($botchJob))
                            <p class="mt-1">{{ __('† Botch Job available') }}</p>
                        @endif
                    </div>
                    @if ($character->trainingSkills->count())
                        <div class="mt-6 sm:mt-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Training') }}</h3>
                            <ul class="space-y-6 sm:space-y-2">
                                @foreach ($character->trainingSkills->sortBy('name') as $characterSkill)
                                    <li class="leading-loose sm:leading-normal">
                                        <span class="cursor-pointer underline decoration-dashed underline-offset-4"
                                              onclick="toggleVisibility('skill-{{ $characterSkill->skill_id }}')">
                                            {{ $characterSkill->name }}
                                            @if($characterSkill->skill->feats->contains(Feat::FLASH_OF_INSIGHT))
                                                *
                                                @php $flashOfInsight = true; @endphp
                                            @endif
                                            <i class="fa-regular fa-circle-question cursor-pointer"
                                               title="{{ __('Show description') }}"
                                            ></i>
                                         </span>
                                        ({{ $characterSkill->trained }}/{{ $characterSkill->cost }})
                                        <a href="{{ route('characters.edit-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"
                                           class="inline-block ml-4 sm:ml-0"
                                        ><i class="fa-solid fa-pencil" title="{{ __('Edit skill') }}"></i><span
                                                class="sm:hidden"> {{ __('Edit') }}</span></a>
                                        @if ($characterSkill->locked)
                                            <i class="fa-solid fa-lockinline-block ml-4 sm:ml-0"
                                               title="{{ __('Expenditure is locked') }}"></i>
                                        @else
                                            <a href="{{ route('characters.remove-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"
                                               class="inline-block ml-4 sm:ml-0"
                                            ><i class="fa-solid fa-trash" title="{{ __('Remove skill') }}"></i><span
                                                    class="sm:hidden"> {{ __('Remove') }}</span></a>
                                        @endif
                                        @if($characterSkill->skill->specialties > 1)
                                            <ul>
                                                @foreach ($characterSkill->skillSpecialties as $specialty)
                                                    <li>{{ $specialty->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <div id="skill-{{ $characterSkill->skill_id }}"
                                             class="text-sm hidden pl-4 space-y-2 mt-1 mb-2">
                                            {!! Str::of($characterSkill->skill->description)->markdown() !!}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            @include('characters.partials.feats-cards')

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
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div class="">
                                    <x-input-label for="skill">{{ __('Skill') }}</x-input-label>
                                    @if ($editSkill && $editSkill->completed)
                                        @php
                                            $skills[] = $editSkill->skill;
                                        @endphp
                                        <input type="hidden" name="skill_id" value="{{$editSkill->skill_id }}">
                                        <x-text-input class="mt-1 block w-full" value="{{ $editSkill->name }}"
                                                      disabled/>
                                    @else
                                        <x-select id="skill" name="skill_id" class="mt-1 block w-full" required
                                                  onchange="showSkillDescription(this.value)">
                                            >
                                            @if ($editSkill)
                                                @php
                                                    $skills[] = $editSkill->skill;
                                                @endphp
                                                <option value="{{ $editSkill->skill_id }}" selected="selected">
                                                    {{ __(':name (:cost months)', ['name' => $editSkill->name, 'cost' => $editSkill->cost]) }}
                                                </option>
                                            @else
                                                <option value="">{{ __('Select a skill') }}</option>
                                            @endif
                                            @foreach($character->availableSkills as $skill)
                                                @if (empty($currentCategory) || $currentCategory != $skill->skill_category_id)
                                                    @if (!empty($currentCategory))
                                                        {!! '</optgroup>' !!}
                                                    @endif
                                                    @php $currentCategory = $skill->skill_category_id; @endphp
                                                    {!! '<optgroup label="' . __(':name Skills', ['name' => $skill->skillCategory->name]) . '">' !!}
                                                @endif
                                                @php
                                                    $skills[] = $skill;
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
                                        </x-select>
                                    @endif
                                </div>

                                @if ($editSkill && $editSkill->skill->specialties > 0)
                                    <div>
                                        <x-input-label for="specialty">{{ __('Specialty') }}
                                            ({{ $editSkill->skill->specialties }})
                                        </x-input-label>
                                        <x-select id="specialty" name="specialty_id[]" class="mt-1 block w-full"
                                                  :multiple="$editSkill->skill->specialties > 1">
                                            @foreach($editSkill->skill->specialtyType->skillSpecialties->sortBy('name') as $specialty)
                                                <option value="{{ $specialty->id }}"
                                                        @if($editSkill->skillSpecialties->contains($specialty)) selected @endif
                                                >
                                                    {{ $specialty->name }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                        @if ($editSkill->skill->specialties > 1)
                                            <p class="text-xs">
                                                {{ __('Press Ctrl to select/de-select additional specialties.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                @if ($editSkill && ($editSkill->discountAvailable || $editSkill->discountedBy->count()))
                                    <div>
                                        <x-input-label
                                            for="discounted_by">{{ __('Available discounts') }}</x-input-label>
                                        <x-select id="discounted_by" name="discounted_by[]" class="mt-1 block w-full"
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
                                        </x-select>
                                    </div>
                                @elseif (!$editSkill)
                                    <div>
                                        <p>{{ __('To apply a discount, first save the skill, then edit the saved skill to select applicable discounts.') }}</p>
                                    </div>
                                @endif

                                @if (Status::APPROVED > $character->status_id)
                                    <div>
                                        <x-input-label for="completed">
                                            {{ __('Completed') }}
                                            <input type="checkbox" id="completed" name="completed" class="" value="1"
                                                   @if (!empty($editSkill) && $editSkill->completed || Status::NEW == $character->status_id) checked="checked" @endif
                                            />
                                        </x-input-label>
                                        <p class="text-xs">
                                            {{ __('Mark as completed if you are buying the full skill, or leave it unchecked if you are only partially investing into it.') }}
                                        </p>
                                        <p class="text-xs">
                                            {{ __('You can have one unfinished skill at the end of character creation - any remaining months will be assigned to it.') }}
                                        </p>
                                    </div>
                                @endif

                                <div class="flex items-center gap-4">
                                    <x-primary-button>{{ __('Save') }}</x-primary-button>
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
                                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                @foreach($skill->feats as $feat)
                                                    <li>
                                                        <span class="underline decoration-dashed"
                                                              onclick="toggleVisibility('feat-{{$skill->id}}-{{ $feat->id }}')">
                                                        {{ $feat->name }}
                                                        <i class="fa-regular fa-circle-question"
                                                           title="{{ $feat->description }}"
                                                        ></i>
                                                        </span>
                                                        <div id="feat-{{$skill->id}}-{{ $feat->id }}"
                                                             class="text-sm hidden pl-4">
                                                            {!! Str::of($feat->description)->markdown() !!}
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if ($skill->cards->count())
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Cards') }}</h4>
                                            <ul class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                                                @foreach($skill->cards as $card)
                                                    <li>
                                                        {{ $card->name }}
                                                        ({{ $card->pivot->number }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if ($skill->unlocks->count() > 0)
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Unlocks') }}</h4>
                                            <ul class="list-inside list-disc">
                                                @foreach($skill->unlocks as $unlock)
                                                    <li>{{ $unlock->skill->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if ($skill->discounts->count() > 0)
                                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Discounts') }}</h4>
                                            <ul class="list-inside list-disc">
                                                @foreach($skill->discounts as $discount)
                                                    <li>{{ sprintf(__('%s (-%d months)'), $discount->discountedSkill->name, $discount->discount) }}</li>
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
