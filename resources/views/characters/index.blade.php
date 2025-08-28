<x-app-layout>
    <x-slot name="title">{{ __('My characters') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My characters') }}
        </h2>
    </x-slot>
    <x-slot name="sidebar2">
        <x-dropdown-link href="{{ route('characters.create') }}">
            <i class="fa-solid fa-plus min-w-8"></i>
            {{ __('Create') }}
        </x-dropdown-link>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <h3 class="text-lg font-semibold">{{ __('Active characters') }}</h3>
        @if (count($activeCharacters) === 0)
            <p>{!! __('You have no active characters. Why not <a href=":link" class="underline">create one</a>?', ['link' => route('characters.create')]) !!}</p>
        @else
            @include('characters.partials.index', ['characters' => $activeCharacters, 'hideUser' => true,])
        @endif
        @if (count($inactiveCharacters) > 0)
            <h3 class="text-lg font-semibold mt-3">{{ __('Inactive characters') }}</h3>
            @include('characters.partials.index', ['characters' => $inactiveCharacters, 'hideUser' => true,])
        @endif
    </div>
</x-app-layout>
