<x-app-layout>
    <x-slot name="title">{{ __('All characters') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All characters') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @if (count($newCharacters) > 0)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <div class="mt-1">
                        <h3 class="text-lg font-semibold">{{ __('New characters') }}</h3>
                        @include('characters.partials.index', ['characters' => $newCharacters, 'hideStatus' => true,])
                    </div>
                </div>
            @endif
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <h3 class="text-lg font-semibold">{{ __('Active characters') }}</h3>
                    @include('characters.partials.index', ['characters' => $activeCharacters])
                </div>
            </div>
            @if (count($inactiveCharacters) > 0)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <div class="mt-1">
                        <h3 class="text-lg font-semibold">{{ __('Inactive characters') }}</h3>
                        @include('characters.partials.index', ['characters' => $inactiveCharacters])
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>