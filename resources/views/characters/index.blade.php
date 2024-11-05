<x-app-layout>
    <x-slot name="title">{{ __('My characters') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My characters') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <ul class="list-disc list-inside">
                        @foreach ($characters as $character)
                            <li>
                                <a href="{{ route('characters.view', $character) }}">
                                    {{ $character->name }}
                                    ({{ $character->background->name }})
                                    - {{ $character->status->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
