@php
    use App\Helpers\CharacterHelper;
    use App\Models\Character;
    use App\Models\User;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('All characters') }}</x-slot>
    <x-slot name="header">
        <div class="sm:float-right grid grid-cols-2 sm:flex gap-4 sm:gap-1 mb-6">
            <a href="{{ route('plotco.print-all') }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Print All Characters') }}"
            >
                <i class="fa-solid fa-print"></i>
                <span class="sm:hidden"> {{ __('Print All') }}</span>
                <span class="hidden sm:inline">{{ __('All') }}</span>
            </a>
            <a onclick="document.getElementById('character_select').submit();"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Print Selected Characters') }}"
            >
                <i class="fa-solid fa-print"></i>
                <span class="sm:hidden"> {{ __('Print Selected') }}</span>
                <span class="hidden sm:inline">{{ __('Selected') }}</span>
            </a>
            <a href="{{ route('plotco.logs') }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Plot Logs') }}"
            >
                <i class="fa-solid fa-clipboard"></i>
                {{ __('Logs') }}
            </a>
        </div>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All characters') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:px-8 sm:py-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <p>
            <strong>{{ __('Lowest training months on an active character:') }}</strong> {{ CharacterHelper::getLowestTrainedMonths() }}
        </p>
        <p>
            <strong>{{ __('Lowest training months on an active character who has done a downtime:') }}</strong> {{ CharacterHelper::getLowestDowntimeMonths() }}
        </p>
        <p>
            <strong>{{ __('Highest training months on an active character:') }}</strong> {{ CharacterHelper::getHighestTrainedMonths() }}
        </p>
        <p>
            <strong>{{ __('Catchup XP:') }}</strong> {{ CharacterHelper::getCatchupXP() }}
        </p>
    </div>

    <form method="GET" action="{{ route('plotco.print-some') }}" id="character_select" class="space-y-6">
        @if (count($newCharacters) > 0)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <h3 class="text-lg font-semibold">{{ __('Characters for approval') }}</h3>
                @include('characters.partials.index', ['characters' => $newCharacters, 'hideStatus' => true, 'checkbox' => true])
            </div>
        @endif

        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <h3 class="text-xl font-semibold">{{ __('Active characters') }}</h3>
            <div class="sm:grid sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-lg">{{ __('Heroes') }}</p>
                    @include('characters.partials.index', ['characters' => $activeCharacters->where('user_id', '!=', User::PLOT_CO_ID)->where('hero_scoundrel', Character::HERO), 'checkbox' => true, 'hideStatus' => true])
                </div>
                <div>
                    <p class="text-lg">{{ __('Scoundrels') }}</p>
                    @include('characters.partials.index', ['characters' => $activeCharacters->where('user_id', '!=', User::PLOT_CO_ID)->where('hero_scoundrel', Character::SCOUNDREL), 'checkbox' => true, 'hideStatus' => true])
                </div>
                <div>
                    <p class="text-lg">{{ __('Unknown') }}</p>
                    @include('characters.partials.index', ['characters' => $activeCharacters->where('user_id', '!=', User::PLOT_CO_ID)->where('hero_scoundrel', Character::UNKNOWN), 'checkbox' => true, 'hideStatus' => true])
                </div>
                <div>
                    <p class="text-lg">{{ __('NPCs') }}</p>
                    @include('characters.partials.index', ['characters' => $activeCharacters->where('user_id', User::PLOT_CO_ID), 'checkbox' => true, 'hideStatus' => true])
                </div>
            </div>
        </div>

        @if (count($inactiveCharacters) > 0)
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <h3 class="text-lg font-semibold">{{ __('Inactive characters') }}</h3>
                @include('characters.partials.index', ['characters' => $inactiveCharacters])
            </div>
        @endif
    </form>
</x-app-layout>
