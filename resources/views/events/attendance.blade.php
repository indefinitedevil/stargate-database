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
            @foreach (Event::where('end_date', '<', date('Y-m-d'))->get() as $event)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <h3 class="text-xl font-semibold">{!! $event->name !!}</h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($event->users as $user)
                            <li>
                                {{ $user->name }}: {{ Event::roleName($user->pivot->role) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
