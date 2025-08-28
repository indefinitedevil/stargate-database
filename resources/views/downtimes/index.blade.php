@php
    use Illuminate\Support\Facades\Auth;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Downtimes') }}</x-slot>
    <x-slot name="header">
        @can('edit downtimes')
            <x-link-button href="{{ route('plotco.downtimes') }}"
               class="float-right">{{ __('Plot Co') }}</x-link-button>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Downtimes') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <ul class="list-disc list-inside space-y-2">
                @if ($downtimes->isEmpty())
                    <li>{{ __('No downtimes available') }}</li>
                @else
                    @foreach($downtimes as $downtime)
                        @if ($downtime->event_id)
                            @php
                                $eventCharacters = $downtime->event->characters()->whereIn('id', $characterIds)->all();
                            @endphp
                            @if (!empty($eventCharacters))
                                <li>
                                    <a href="{{ route($downtime->isOpen() ? 'downtimes.submit' : 'downtimes.view', ['downtimeId' => $downtime->id, current($eventCharacters)->id]) }}"
                                       class="underline">{{ $downtime->name }}
                                        ({{ $downtime->event->name }})
                                        - {{ current($eventCharacters)->listName }}</a>
                                    ({{ format_datetime($downtime->start_time, 'd/m/Y H:i') }}
                                    - {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }})
                                    - {{ $downtime->getStatusLabel() }}
                                </li>
                            @else
                                <li>
                                    {{ $downtime->name }}
                                    ({{ $downtime->event->name }})
                                    ({{ format_datetime($downtime->start_time, 'd/m/Y H:i') }}
                                    - {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                    <ul class="list-inside list-disc ml-4">
                                        @if ($downtime->event->users->where('id', Auth::user()->id)->count())
                                            @foreach ($activeCharacterIds as $characterId)
                                                <li>
                                                    <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $characterId]) }}"
                                                       class="underline">
                                                        {{ __('Downtime actions for :character', ['character' => Auth::user()->getCharacter($characterId)->listName]) }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @else
                                            <li>{{ __('You do not appear to be eligible for this downtime.') }}</li>
                                        @endif
                                    </ul>
                                </li>
                            @endif
                        @else
                            @php
                                $actions = $downtime->actions()->whereIn('character_id', $characterIds)->get();
                            @endphp
                            @if ($actions->count())
                                @php
                                    $character = $actions->first()->character;
                                @endphp
                                <li>
                                    <a href="{{ route($downtime->isOpen() ? 'downtimes.submit' : 'downtimes.view', ['downtimeId' => $downtime->id, $character->id]) }}"
                                       class="text-blue-500 hover:underline">
                                        {{ $downtime->name }} - {{ $character->listName }}
                                    </a>
                                    ({{ format_datetime($downtime->start_time, 'd/m/Y H:i') }}
                                    - {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                </li>
                            @else
                                <li>
                                    {{ $downtime->name }}
                                    ({{ format_datetime($downtime->start_time, 'd/m/Y H:i') }}
                                    - {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                    <ul class="list-inside list-disc ml-4">
                                        @foreach ($activeCharacterIds as $characterId)
                                            <li>
                                                <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $characterId]) }}"
                                                   class="text-blue-500 hover:underline">
                                                    {{ __('Downtime actions for :character', ['character' => Auth::user()->getCharacter($characterId)->listName]) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <p>{{ __('Downtimes open between events to allow for character development to occur.') }}</p>
            <p>{{ __('When a downtime period is open, if you are eligible for it, you can submit downtime actions for your character.') }}</p>
            <p>{{ __('Actions are split into Development actions where you train skills, teach others, and go on missions; Research actions which are up to the Plot Co to determine how they\'re used; and Miscellaneous actions where you can leave information for the Plot Co.') }}</p>
            <p>{{ __('The number of actions available is determined by the Plot Co for any given downtime.') }}</p>
            <p>{{ __('If you are bringing in a new character, you get catchup training to minimise the training advantage existing characters have.') }}</p>
        </div>
    </div>
</x-app-layout>
