<x-app-layout>
    <x-slot name="title">{{ __('All characters') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('All characters') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <h3 class="text-lg font-semibold">{{ __('New characters') }}</h3>
                    <ul class="list-disc list-inside">
                        @if (count($newCharacters) == 0)
                            <li>{{ __('No characters found') }}</li>
                        @else
                            @foreach ($newCharacters as $character)
                                <li>
                                    <a class="underline" href="{{ route('characters.view', $character) }}">
                                        {{ $character->name }}</a>
                                    ({{ $character->background->name }})
                                    [{{ $character->user->name }}]
                                    - {{ $character->status->name }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    <h3 class="text-lg font-semibold mt-3">{{ __('Active characters') }}</h3>
                    <ul class="list-disc list-inside">
                        @if (count($activeCharacters) == 0)
                            <li>{{ __('No characters found') }}</li>
                        @else
                            @foreach ($activeCharacters as $character)
                                <li>
                                    <a class="underline" href="{{ route('characters.view', $character) }}">
                                        {{ $character->name }}</a>
                                    ({{ $character->background->name }})
                                    [{{ $character->user->name }}]
                                    - {{ $character->status->name }}
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    @if (count($inactiveCharacters) > 0)
                        <h3 class="text-lg font-semibold mt-3">{{ __('Inactive characters') }}</h3>
                        <ul class="list-disc list-inside">
                            @foreach ($inactiveCharacters as $character)
                                <li>
                                    <a class="underline" href="{{ route('characters.view', $character) }}">
                                        {{ $character->name }}</a>
                                    ({{ $character->background->name }})
                                    [{{ $character->user->name }}]
                                    - {{ $character->status->name }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
