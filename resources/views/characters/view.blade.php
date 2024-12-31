@php
    use App\Models\Feat;
    use App\Models\Status;
@endphp
<x-app-layout>
    <x-slot name="title">{{ $character->name }}</x-slot>
    <x-slot name="header">
        <a href="{{ route('characters.print', ['characterId' => $character->id]) }}"
           class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
        >{{ __('Print') }}</a>
        @can('delete', $character)
            <a href="{{ route('characters.delete', ['characterId' => $character->id]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
            >{{ __('Delete') }}</a>
        @endcan
        @can('edit', $character)
            <a href="{{ route('characters.edit', ['characterId' => $character->id]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
            >{{ __('Edit') }}</a>
        @endcan
        @can('approve', $character)
            <a href="{{ route('characters.approve', ['characterId' => $character->id]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
            >{{ __('Approve') }}</a>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ sprintf(__('Character: %s'), $character->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                @include('characters.partials.details', ['character' => $character])
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="">
                    @can('edit', $character)
                        <a href="{{ route('characters.edit-skills', ['characterId' => $character->id]) }}"
                           class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        >{{ __('Edit Skills') }}</a>
                    @endcan
                    <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Skills') }}
                    </h2>
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
                                @foreach ($character->displayedTrainedSkills->sortBy('name') as $characterSkill)
                                    <li>{{ $characterSkill->name }}
                                        @if($characterSkill->skill->feats->contains(Feat::FLASH_OF_INSIGHT))
                                            *
                                            @php $flashOfInsight = true; @endphp
                                        @endif
                                        @if($characterSkill->skill->specialties > 1)
                                            <ul class="list-disc list-inside">
                                                @foreach ($characterSkill->allSpecialties as $specialty)
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
                    </div>
                    @if(!empty($flashOfInsight))
                        <p class="mt-1">{{ __('* Flash of Insight discount available') }}</p>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="">
                    <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Feats') }}
                    </h2>
                    <ul class="grid grid-cols-4 mt-1">
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

            @if (!empty($character->cards))
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Cards') }}
                        </h2>
                        <ul class="grid grid-cols-4 mt-1">
                            @foreach ($character->cards as $card)
                                <li>{{ $card->name }} ({{ $card->number }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="">
                    <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('History') }}
                    </h2>
                    <p class="mt-1">{!! nl2br($character->history) !!}</p>
                </div>
            </div>
            @can('view hidden notes')
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Plot notes') }}
                        </h2>
                        <p class="mt-1">{!! nl2br($character->plot_notes) !!}</p>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
