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

    @include('plotco.partials.approval')
    @include('characters.partials.reset')
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        @include('characters.partials.details')
    </div>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="grid grid-cols-1 sm:grid-cols-4 clear-both gap-6">
            @if ($character->status_id < Status::APPROVED)
                <div>
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
                                    {!! process_markdown($skill->description) !!}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div>
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
                                {!! process_markdown($characterSkill->skill->description) !!}
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if (in_array($character->status_id, [Status::NEW, Status::READY]))
                    <p class="mt-1">
                        {{ sprintf(__('Total training: %d / %s'), $character->trainingMonths, $character->background->adjustedMonths) }}
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
                <div>
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
                                    {!! process_markdown($characterSkill->skill->description) !!}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    @include('characters.partials.feats-cards')
    @include('characters.partials.add-skill')
</x-app-layout>
