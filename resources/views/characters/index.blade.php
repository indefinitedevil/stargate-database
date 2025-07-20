<x-app-layout>
    <x-slot name="title">{{ __('My characters') }}</x-slot>
    <x-slot name="header">
        <x-link-button href="{{ route('characters.create') }}" class="float-right">{{ __('Create') }}</x-link-button>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My characters') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="mt-1">
            <h3 class="text-lg font-semibold">{{ __('Active characters') }}</h3>
            @include('characters.partials.index', ['characters' => $activeCharacters, 'hideUser' => true,])
            @if (count($inactiveCharacters) > 0)
                <h3 class="text-lg font-semibold mt-3">{{ __('Inactive characters') }}</h3>
                @include('characters.partials.index', ['characters' => $inactiveCharacters, 'hideUser' => true,])
            @endif
        </div>
    </div>
</x-app-layout>
