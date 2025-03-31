@php
    use Illuminate\Support\Facades\Auth;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Downtimes') }}</x-slot>
    <x-slot name="header">
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
                                    ({{ $downtime->start_time->format('d/m/Y H:i') }}
                                    - {{ $downtime->end_time->format('d/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                </li>
                            @else
                                <li>
                                    {{ $downtime->name }}
                                    ({{ $downtime->event->name }})
                                    ({{ $downtime->start_time->format('d/m/Y H:i') }}
                                    - {{ $downtime->end_time->format('d/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                    <ul class="list-inside list-disc ml-4">
                                        @foreach ($activeCharacterIds as $characterId)
                                            <li>
                                                <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $characterId]) }}"
                                                   class="underline">
                                                    {{ __('Submit for :character', ['character' => Auth::user()->getCharacter($characterId)->listName]) }}
                                                </a>
                                            </li>
                                        @endforeach
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
                                    ({{ $downtime->start_time->format('d/m/Y H:i') }}
                                    - {{ $downtime->end_time->format('d/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                </li>
                            @else
                                <li>
                                    {{ $downtime->name }}
                                    ({{ $downtime->start_time->format('d/m/Y H:i') }}
                                    - {{ $downtime->end_time->format('d/m/Y H:i') }})
                                    - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                    <ul class="list-inside list-disc ml-4">
                                        @foreach ($activeCharacterIds as $characterId)
                                            <li>
                                                <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $characterId]) }}"
                                                   class="text-blue-500 hover:underline">
                                                    {{ __('Submit for :character', ['character' => Auth::user()->getCharacter($characterId)->listName]) }}
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
            <p>Welcome to the Stargate downtime system. Downtimes open between events - usually for players and crew who were present at those events, but there are exceptions to that rule that aren't relevant to this explainer.</p>
            <p>When a downtime period is open, if you are eligible for it, you should be able to submit downtime actions for your character.</p>
            <p>Actions are split into Development actions where you train skills, teach others, and go on missions; Research actions which are up to the plot co to determine how they're used; and Miscellaneous actions were you can leave information for the plot co.</p>
            <p>The number of actions available is down to the plot co's decision for any given downtime.</p>
        </div>
    </div>
</x-app-layout>
