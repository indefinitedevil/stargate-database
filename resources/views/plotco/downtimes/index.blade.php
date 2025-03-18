<x-app-layout>
    <x-slot name="title">{{ __('Downtimes') }}</x-slot>
    <x-slot name="header">
        @can('edit downtimes')
            <a href="{{ route('plotco.downtimes.create') }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            >{{ __('Create') }}</a>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Downtimes') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <ul class="list-disc list-inside">
                @if ($downtimes->isEmpty())
                    <li>{{ __('No downtimes available') }}</li>
                @else
                    @foreach($downtimes as $downtime)
                        <li>
                            <a href="{{ route('plotco.downtimes.edit', ['downtimeId' => $downtime->id]) }}"
                               class="underline">{{ $downtime->name }}</a>
                            ({{ $downtime->start_time->format('d/m/Y') }}
                            - {{ $downtime->end_time->format('d/m/Y') }})
                            - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}

                            @if ($downtime->event_id)
                                - Attached to {{ $downtime->event->name }}
                            @endif
                            <ul class="list-disc list-inside pl-4 sm:grid sm:grid-cols-6">
                                @foreach ($downtime->getCharacters() as $character)
                                    <li>
                                        <a class="underline"
                                           href="{{ route('downtimes.view', ['downtimeId' => $downtime->id, 'characterId' => $character->id]) }}">
                                            {{ $character->listName }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</x-app-layout>
