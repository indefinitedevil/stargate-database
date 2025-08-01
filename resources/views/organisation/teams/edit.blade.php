@php
    use App\Models\Event;
    $title = empty($team->id) ? __('Create team') : sprintf(__('Edit team: %s'), $team->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="mt-1">
            <form method="POST" action="{{ route('teams.store') }}">
                @csrf
                @if (!empty($team->id))
                    <input type="hidden" name="id" value="{{ $team->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-5 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-5">
                        <x-input-label for="name" :value="__('Team Name')"/>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $team->name ?? '')" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="event_id" :value="__('Event (optional)')"/>
                        <x-select id="event_id" name="event_id" class="mt-1 block w-full">
                            <option value="">{{ __('Select an event for temporary teams') }}</option>
                            @foreach(Event::all() as $event)
                                <option value="{{ $event->id }}"
                                        @if(old('event_id', $team->event_id ?? '') == $event->id) selected @endif>{{ $event->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('event_id')"/>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="event_id" :value="__('Event (optional)')"/>
                        <x-select id="event_id" name="event_id" class="mt-1 block w-full">
                            <option value="">{{ __('Select an event for temporary teams') }}</option>
                            @foreach(Event::all() as $event)
                                <option value="{{ $event->id }}"
                                        @if(old('event_id', $team->event_id ?? '') == $event->id) selected @endif>{{ $event->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('event_id')"/>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="event_id" :value="__('Event (optional)')"/>
                        <x-select id="event_id" name="event_id" class="mt-1 block w-full">
                            <option value="">{{ __('Select an event for temporary teams') }}</option>
                            @foreach(Event::all() as $event)
                                <option value="{{ $event->id }}"
                                        @if(old('event_id', $team->event_id ?? '') == $event->id) selected @endif>{{ $event->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('event_id')"/>
                    </div>

                    <div class="col-span-3 row-span-3">
                        <x-input-label for="description" :value="__('Description')"/>
                        <x-textarea id="description" name="description" rows="6"
                                    class="mt-1 block w-full">{{ $team->description ?? '' }}</x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                        <p class="text-xs mt-1">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
