<x-app-layout>
    <x-slot name="title">{{ __('Downtimes') }}</x-slot>
    <x-slot name="header">
        @can('edit downtimes')
            <x-link-button href="{{ route('plotco.downtimes.create') }}"
               class="float-right"
            >{{ __('Create') }}</x-link-button>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Downtimes') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <ul class="list-disc list-inside space-y-2">
                @if ($downtimes->isEmpty())
                    <li>{{ __('No downtimes available') }}</li>
                @else
                    @foreach($downtimes as $downtime)
                        <li>
                            <a href="{{ route('plotco.downtimes.edit', ['downtimeId' => $downtime->id]) }}"
                               class="underline">{{ $downtime->name }}</a>
                            @if ($downtime->event_id)
                                ({{ $downtime->event->name }})
                            @endif
                                ({{ format_datetime($downtime->start_time, 'd/m/Y') }}
                                - {{ format_datetime($downtime->end_time, 'd/m/Y') }})
                            - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                            <a href="{{ route('plotco.downtimes.preprocess', ['downtimeId' => $downtime->id]) }}"
                               class="underline ps-3"><i class="fa-solid fa-file-check"></i> {{ __('View actions') }}</a>

                            <ul class="list-disc list-inside pl-4 sm:grid sm:grid-cols-6">
                                <li>{{ __(':count / :eligible submitted', ['count' => $downtime->getCharacters()->count(), 'eligible' => $downtime->getEligibleUsers()->count()]) }}</li>
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

    <div class="mt-6">
        {{ $downtimes->links() }}
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <h3 class="text-lg font-semibold">{{ __('How to use the downtime system') }}</h3>
            <ul class="list-inside list-decimal">
                <li>{{ __('Create a downtime. Should be fairly self-explanatory. Choose a start time and an end time and say whether it is associated with an event.') }}</li>
                <li>{{ __('When you do so, all players on the system will get an email telling them about it.') }}</li>
                <li>{{ __('While the downtime is active, you can see players saved actions.') }}</li>
                <li>{{ __('When downtime is closed, review the changes that the system will make on the Preprocess screen then click Process to process the downtime.') }}</li>
                <li>{{ __('All players with downtimes will get an email which contains the changes to their characters.') }}</li>
            </ul>
        </div>
    </div>
</x-app-layout>
