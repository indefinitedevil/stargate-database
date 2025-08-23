@php use App\Models\Event; @endphp
<x-app-layout>
    <x-slot name="title">{{ __('Attendance') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    @foreach ($currentEvents as $event)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            @can('view all characters')
                <x-link-button href="{{ route('plotco.print-some', ['event' => $event->id]) }}"
                               class="float-right"
                               title="{{ __('Print Characters') }}"
                >
                    <i class="fa-solid fa-print"></i>
                    {{ __('Print') }}
                </x-link-button>
            @endcan
            @can('record attendance')
                <x-link-button href="{{ route('events.attendance', $event) }}"
                               class="float-right"
                               title="{{ __('Record attendance') }}"
                >
                    <i class="fa-solid fa-pen-to-square"></i>
                    {{ __('Record Attendance') }}
                </x-link-button>
            @endcan
            <h3 class="text-xl font-semibold">{!! $event->name !!}</h3>
            <ul class="sm:grid sm:grid-cols-3 gap-2 clear-both">
                @foreach($event->users as $user)
                    <li>
                        {{ $user->name }}:
                        @switch($user->pivot->role)
                            @case(Event::ROLE_RUNNER)
                                {{ __('Event Runner') }}
                                @break
                            @case(Event::ROLE_CREW)
                                {{ __('Crew') }}
                                @break
                            @default
                                @if ($user->pivot->character_id)
                                    <a class="underline"
                                       href="{{ route('characters.view', ['characterId' => $user->pivot->character_id]) }}">
                                        {{ $user->characters->firstWhere('id', $user->pivot->character_id)->listName ?? __('No character') }}
                                    </a>
                                @else
                                    {{ __('No character') }}
                                @endif
                                @if (Event::ROLE_PAID_DOWNTIME === $user->pivot->role)
                                    ({{ __('Paid Downtime') }})
                                @endif
                                @break
                        @endswitch
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <h3 class="text-lg font-semibold">{{ __('Past events') }}</h3>
        <ul class="list-disc list-inside">
            @foreach ($pastEvents as $event)
                <li>
                    <strong>{{ $event->name }}:</strong> {{ format_datetime($event->start_date, 'd/m/y') }}
                    - {{ format_datetime($event->end_date, 'd/m/y') }}
                    @can('record attendance')
                        <a class="underline ms-6" href="{{ route('events.attendance', $event) }}">
                            <i class="fa-solid fa-pen-to-square"></i>
                            {{ __('Record Attendance') }}
                        </a>
                    @endcan
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
