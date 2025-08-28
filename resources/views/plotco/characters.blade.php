@php
    use App\Helpers\CharacterHelper;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('All characters') }}</x-slot>
    <x-slot name="header">
        <div class="sm:float-right grid grid-cols-2 sm:flex gap-4 sm:gap-1 mb-6">
            <x-link-button href="{{ route('plotco.print-all') }}" class="float-right"
                           title="{{ __('Print All Characters') }}"
            >
                <i class="fa-solid fa-print"></i>
                <span class="sm:hidden"> {{ __('Print All') }}</span>
                <span class="hidden sm:inline">{{ __('All') }}</span>
            </x-link-button>
            <x-link-button onclick="document.getElementById('character_select').submit();"
                           class="float-right" title="{{ __('Print Selected Characters') }}"
            >
                <i class="fa-solid fa-print"></i>
                <span class="sm:hidden"> {{ __('Print Selected') }}</span>
                <span class="hidden sm:inline">{{ __('Selected') }}</span>
            </x-link-button>
            <x-link-button href="{{ route('plotco.logs') }}"
                           class="float-right"
                           title="{{ __('Plot Logs') }}"
            >
                <i class="fa-solid fa-clipboard"></i>
                {{ __('Logs') }}
            </x-link-button>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All characters') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:px-8 sm:py-4 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <p>
            <strong>{{ __('Lowest training months on an active character:') }}</strong> {{ CharacterHelper::getLowestTrainingMonths() }}
        </p>
        <p>
            <strong>{{ __('Lowest training months on an active character who has done a downtime:') }}</strong> {{ CharacterHelper::getLowestTrainingMonthsIncludingDowntime() }}
            @php
            $characterId = CharacterHelper::getLowestPostCreationTrainingMonthsIncludingDowntimeCharacterId();
            @endphp
            @if ($characterId)
                ({{ CharacterHelper::getCharacterById($characterId)->listName }})
            @endif
        </p>
        <p>
            <strong>{{ __('Highest training months on an active character:') }}</strong> {{ CharacterHelper::getHighestTrainingMonths() }}
        </p>
        <p>
            <strong>{{ __('Catchup XP:') }}</strong> {{ CharacterHelper::getCatchupXP() }}
        </p>
    </div>

    <form method="GET" action="{{ route('plotco.print-some') }}" id="character_select" class="space-y-6">
        @if (count($newCharacters) > 0)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
                <h3 class="text-lg font-semibold">{{ __('Characters for approval') }}</h3>
                @include('characters.partials.index', ['characters' => $newCharacters, 'hideStatus' => true, 'checkbox' => true])
            </div>
        @endif

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
            <h3 class="text-xl font-semibold">{{ __('Active characters') }}</h3>
            <div class="sm:grid sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-lg">{{ __('Heroes') }}</p>
                    @include('characters.partials.index', ['characters' => $heroCharacters, 'checkbox' => true, 'hideStatus' => true])
                </div>
                <div>
                    <p class="text-lg">{{ __('Scoundrels') }}</p>
                    @include('characters.partials.index', ['characters' => $scoundrelCharacters, 'checkbox' => true, 'hideStatus' => true])
                </div>
                <div>
                    <p class="text-lg">{{ __('Unknown') }}</p>
                    @include('characters.partials.index', ['characters' => $unknownCharacters, 'checkbox' => true, 'hideStatus' => true])
                </div>
                <div>
                    <p class="text-lg">{{ __('Villains/NPCs') }}</p>
                    <div class="divide-y divide-gray-100">
                        @include('characters.partials.index', ['characters' => $villainCharacters, 'checkbox' => true, 'hideStatus' => true])
                        @include('characters.partials.index', ['characters' => $plotcoCharacters, 'checkbox' => true, 'hideStatus' => true])
                    </div>
                </div>
            </div>
        </div>

        @if (count($inactiveCharacters) > 0)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
                <h3 class="text-lg font-semibold">{{ __('Inactive characters') }}</h3>
                @include('characters.partials.index', ['characters' => $inactiveCharacters])
            </div>
        @endif
    </form>
</x-app-layout>
