@php
    use App\Helpers\CharacterHelper;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('All characters') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All characters') }}
        </h2>
    </x-slot>
    <x-slot name="sidebar2">
        <x-dropdown-link href="{{ route('plotco.print-all') }}">
            <i class="fa-solid fa-print min-w-8"></i>
            {{ __('Print All') }}
        </x-dropdown-link>
        <x-dropdown-link class="cursor-pointer" onclick="document.getElementById('character_select').submit();">
            <i class="fa-solid fa-print min-w-8"></i>
            {{ __('Print Selected') }}
        </x-dropdown-link>
        <x-dropdown-link href="{{ route('plotco.logs') }}">
            <i class="fa-solid fa-clipboard min-w-8"></i>
            {{ __('Character Logs') }}
        </x-dropdown-link>
    </x-slot>

    <div class="p-4 sm:px-8 sm:py-4 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <p>
            <strong>{{ __('Lowest training months on an active character:') }}</strong> {{ CharacterHelper::getLowestTrainingMonths() }}
            @php
                $characterId = CharacterHelper::getLowestTrainingMonthsCharacterId();
            @endphp
            @if ($characterId)
                ({{ CharacterHelper::getCharacterById($characterId)->listName }})
            @endif
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
                        @if (!$villainCharacters->isEmpty())
                        @include('characters.partials.index', ['characters' => $villainCharacters, 'checkbox' => true, 'hideStatus' => true])
                        @endif
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
