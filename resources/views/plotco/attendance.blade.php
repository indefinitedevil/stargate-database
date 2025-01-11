@php
    use App\Models\Event;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Attendance') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @foreach (Event::where('end_date', '>', date('Y-m-d'))->get() as $event)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <a href="{{ route('plotco.print-some', ['event' => $event->id]) }}"
                       class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                       title="{{ __('Print Characters') }}"
                    ><i class="fa-solid fa-print"></i></a>
                    <h3 class="text-xl font-semibold">{!! $event->name !!}</h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($event->characters as $character)
                            <li>
                                {{ $character->user->name }}:
                                <a class="underline" href="{{ route('characters.view', ['characterId' => $character->id]) }}">
                                    {{ $character->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
