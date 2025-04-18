@php
    use App\Models\Event;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Events') }}</x-slot>
    <x-slot name="header">
        @can('edit events')
            <a href="{{ route('events.create') }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            >{{ __('Create') }}</a>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <h3 class="text-lg font-semibold">{{ __('Upcoming events') }}</h3>
            <ul class="space-y-6">
                @php $events = Event::where('end_date', '>', date('Y-m-d'))->get(); @endphp
                @if (count($events) == 0)
                    <li>{{ __('No events found') }}</li>
                @else
                    @foreach ($events as $event)
                        <li>
                            <strong>{{ $event->name }}:</strong> {{ format_datetime($event->start_date, 'd/m/y') }}
                            - {{ format_datetime($event->end_date, 'd/m/y') }}
                            @can('edit events')
                                <a class="underline ms-6" href="{{ route('events.edit', $event) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    {{ __('Edit') }}
                                </a>
                            @endcan
                            @can('record attendance')
                                <a class="underline ms-6" href="{{ route('events.attendance', $event) }}">
                                    <i class="fa-solid fa-file-check"></i>
                                    {{ __('Record Attendance') }}
                                </a>
                            @endcan
                            <p>{{ __('Location: :location', ['location' => $event->location]) }}</p>
                            <p>{!! process_markdown($event->description) !!}</p>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <div class="mt-4">
            @php $events = Event::where('end_date', '<', date('Y-m-d'))->get(); @endphp
            @if (count($events))
                <h3 class="text-lg font-semibold">{{ __('Past events') }}</h3>
                <ul class="list-disc list-inside">
                    @foreach ($events as $event)
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
            @endif
        </div>
    </div>
</x-app-layout>
