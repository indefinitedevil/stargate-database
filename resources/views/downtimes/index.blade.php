<x-app-layout>
    <x-slot name="title">{{ __('Downtimes') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Downtimes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <ul class="list-disc list-inside">
                        @foreach($downtimes as $actions)
                            @foreach($actions as $action)
                                <li>
                                    <a href="{{ route('downtimes.view', ['characterId' => $action->character_id, 'downtimeId' => $action->downtime_id]) }}"
                                       class="text-blue-500 hover:underline">
                                        {{ $action->downtime->name }} - {{ $action->character->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
