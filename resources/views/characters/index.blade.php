<x-app-layout>
    <x-slot name="title">{{ __('My characters') }}</x-slot>
    <x-slot name="header">
        <a href="{{ route('characters.create') }}"
           class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >{{ __('Create') }}</a>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My characters') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <ul class="list-disc list-inside">
                        @if (count($characters) == 0)
                            <li>{{ __('No characters found') }}</li>
                        @else
                            @foreach ($characters as $character)
                                <li>
                                    <a href="{{ route('characters.view', $character) }}">
                                        {{ $character->name }}
                                        ({{ $character->background->name }})
                                        - {{ $character->status->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
