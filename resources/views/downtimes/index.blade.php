@php
    use App\Models\Downtime;
    use Illuminate\Support\Facades\Auth;
@endphp
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
                        @php
                            $characterIds = Auth::user()->characters->pluck('id')->toArray();
                        @endphp
                        @if ($downtimes->isEmpty())
                            <li>{{ __('No downtimes available') }}</li>
                        @else
                            @foreach($downtimes as $downtime)
                                @if ($downtime->event_id)
                                    @php
                                        $event = $downtime->event;
                                        $eventCharacters = $event->characters()->whereIn('id', $characterIds)->all();
                                    @endphp
                                    @if (!empty($eventCharacters))
                                        <li>
                                            <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $eventCharacters[0]->id]) }}"
                                               class="text-blue-500 hover:underline">{{ $downtime->name }}
                                                - {{ $eventCharacters[0]->name }}</a>
                                            ({{ $downtime->start_time->format('d/m/Y') }}
                                            - {{ $downtime->end_time->format('d/m/Y') }})
                                            - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
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
                                            <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $character->id]) }}"
                                               class="text-blue-500 hover:underline">
                                                {{ $downtime->name }} - {{ $character->name }}
                                            </a>
                                            ({{ $downtime->start_time->format('d/m/Y') }}
                                            - {{ $downtime->end_time->format('d/m/Y') }})
                                            - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                        </li>
                                    @else
                                        @foreach ($characterIds as $characterId)
                                            <li>
                                                <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime->id, $characterId]) }}"
                                                   class="text-blue-500 hover:underline">
                                                    {{ $downtime->name }}
                                                    - {{ Auth::user()->getCharacter($characterId)->name }}
                                                </a>
                                                ({{ $downtime->start_time->format('d/m/Y') }}
                                                - {{ $downtime->end_time->format('d/m/Y') }})
                                                - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                                            </li>
                                        @endforeach
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
