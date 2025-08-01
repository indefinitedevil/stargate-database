@php
    use App\Models\Feat;
    use App\Models\Status;
@endphp
<x-app-layout>
    <x-slot name="title">{{ $character->name }}</x-slot>
    <x-slot name="header">
        @include('characters.partials.actions')
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ sprintf(__('Character: %s'), $character->name) }}
            @if($character->isPrimary)
                <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i>
            @endif
        </h2>
    </x-slot>

    @include('plotco.partials.approval')
    @include('characters.partials.reset')
    @include('characters.partials.details')

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        @can('edit', $character)
            <x-link-button href="{{ route('characters.edit-skills', ['characterId' => $character->id]) }}"
                           class="float-right">
                <i class="fa-solid fa-pen-to-square"></i>
                &nbsp;
                <span class="sm:hidden"> {{ __('Edit Skills') }}</span>
                <span class="hidden sm:inline">{{ __('Skills') }}</span>
            </x-link-button>
        @endcan
        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
            {{ __('Skills') }}
        </h2>
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
                    @foreach ($character->displayedTrainedSkills->sortBy('name') as $characterSkill)
                        <li>
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
                            @if($characterSkill->skill->specialties > 1)
                                <ul class="list-disc list-inside">
                                    @foreach ($characterSkill->allSpecialties as $specialty)
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
                        {{ sprintf(__('Total training: %d / %s'), $character->completedTrainingMonths, $character->background->adjustedMonths) }}
                    </p>
                @endif
            </div>
            @if ($character->trainingSkills->count())
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Training') }}</h3>
                    <ul class="space-y-6 sm:space-y-2">
                        @foreach ($character->trainingSkills->sortBy('name') as $characterSkill)
                            <li>
                                <span class="cursor-pointer underline decoration-dashed underline-offset-4"
                                      onclick="toggleVisibility('skill-{{ $characterSkill->skill_id }}')">
                                    {{ $characterSkill->name }}
                                    <i class="fa-regular fa-circle-question cursor-pointer"
                                       title="{{ __('Show description') }}"
                                    ></i>
                                 </span>
                                ({{ $characterSkill->trained }}/{{ $characterSkill->cost }})
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
        @if(!empty($flashOfInsight))
            <p class="mt-4">{{ __('* Flash of Insight discount available') }}</p>
        @endif
        @if(!empty($botchJob))
            <p class="mt-4">{{ __('† Botch Job available') }}</p>
        @endif
    </div>

    @include('characters.partials.feats-cards')

    @if (!empty($character->abilities()))
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <div class="">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Abilities') }}
                </h2>
                <div class="mt-1">
                    <ul class="sm:grid sm:grid-cols-4 gap-x-6 mt-1 gap-y-6 sm:gap-y-2">
                        @foreach ($character->abilities() as $ability)
                            <li>{{ $ability }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($character->other_abilities))
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                {{ __('Other abilities') }}
            </h2>
            <div class="mt-1 space-y-6">{!! process_markdown($character->other_abilities) !!}</div>
        </div>
    @endif

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
            {{ __('History') }}
        </h2>
        <div class="mt-1 space-y-6">{!! process_markdown($character->history) !!}</div>
    </div>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
            {{ __('Pre-Existing Character Links') }}
        </h2>
        <div class="mt-1 space-y-6">{!! process_markdown($character->character_links) !!}</div>
    </div>

    @if (empty(request()->input('as_player')))
        @can('view hidden notes')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Plot notes') }}
                </h2>
                <div class="mt-1 space-y-6">{!! process_markdown($character->plot_notes) !!}</div>
            </div>
        @endcan
        @include('characters.partials.add-log')
    @endif
    <script src="{{ asset('js/characters.js') }}" defer></script>
</x-app-layout>
