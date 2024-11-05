@php use App\Models\Feat; @endphp
<x-app-layout>
    <x-slot name="title">{{ $character->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ sprintf(__('Character: %s'), $character->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="grid grid-cols-4">
                    <p class="mt-1">
                        <strong>{{ __('Name') }}:</strong> {{ $character->name }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Body') }}:</strong> {{ $character->body }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }}
                    </p>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="">
                    <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Skills') }}
                    </h2>
                    <div class="grid grid-cols-4">
                        <div class="mt-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Background</h3>
                            <ul>
                                @foreach ($character->background->skills as $skill)
                                    <li>{{ $skill->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mt-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Trained</h3>
                            <ul>
                                @foreach ($character->displayedTrainedSkills->sortBy('name') as $characterSkill)
                                    <li>{{ $characterSkill->name }}
                                        @if($characterSkill->skill->feats->contains(Feat::FLASH_OF_INSIGHT))
                                            *
                                            @php $flashOfInsight = true; @endphp
                                        @endif
                                        @if($characterSkill->skill->specialties > 1)
                                            <ul>
                                                @foreach ($characterSkill->specialties as $specialty)
                                                    <li>{{ $specialty->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @if ($character->trainingSkills->count())
                            <div class="mt-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Training</h3>
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
                        <p class="mt-1">* Flash of Insight discount available</p>
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
        </div>
    </div>
</x-app-layout>
