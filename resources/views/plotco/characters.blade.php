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
        </div>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All characters') }}
        </h2>
    </x-slot>

    <form method="GET" action="{{ route('plotco.print-some') }}" id="character_select" class="space-y-6">
        @if (count($newCharacters) > 0)
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <h3 class="text-lg font-semibold">{{ __('Characters for approval') }}</h3>
                    @include('characters.partials.index', ['characters' => $newCharacters, 'hideStatus' => true, 'checkbox' => true])
                </div>
            </div>
        @endif
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <div class="mt-1">
                <h3 class="text-lg font-semibold">{{ __('Active characters') }}</h3>
                @include('characters.partials.index', ['characters' => $activeCharacters, 'checkbox' => true])
            </div>
        </div>
        @if (count($inactiveCharacters) > 0)
            <div
                class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <h3 class="text-lg font-semibold">{{ __('Inactive characters') }}</h3>
                    @include('characters.partials.index', ['characters' => $inactiveCharacters, 'checkbox' => true])
                </div>
            </div>
        @endif
    </form>
</x-app-layout>
