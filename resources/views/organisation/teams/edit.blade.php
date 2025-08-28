@php
    $title = empty($team->id) ? __('Create team') : sprintf(__('Edit team: %s'), $team->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="mt-1">
            <form method="POST" action="{{ route('teams.store') }}">
                @csrf
                @if (!empty($team->id))
                    <input type="hidden" name="id" value="{{ $team->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-2 space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Team Name')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="old('name', $team->name ?? '')" required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')"/>
                            <x-textarea id="description" name="description" rows="4"
                                        class="mt-1 block w-full">{{ $team->description ?? '' }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                            <p class="text-xs mt-1">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        </div>
                    </div>

                    <div class="col-span-2 space-y-6">
                        <div>
                            <x-input-label for="event_id" :value="__('Event (optional)')"/>
                            <x-select id="event_id" name="event_id" class="mt-1 block w-full">
                                <option value="">{{ __('Select an event for temporary teams') }}</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}"
                                            @if(old('event_id', $team->event_id ?? '') == $event->id) selected @endif>{{ $event->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('event_id')"/>
                        </div>

                        <div>
                            <x-input-label for="team_lead" :value="__('Team Leader')"/>
                            <x-select id="team_lead" name="team_lead" class="mt-1 block w-full">
                                <option value="">{{ __('Select a team leader') }}</option>
                                @foreach ($activeCharacters as $character)
                                    <option value="{{ $character->id }}"
                                            @if(old('team_lead', $team->team_lead_id ?? '') == $character->id) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('team_lead')"/>
                        </div>
                        <div>
                            <x-input-label for="team_second" :value="__('Team 2IC')"/>
                            <x-select id="team_second" name="team_second" class="mt-1 block w-full">
                                <option value="">{{ __('Select a team second') }}</option>
                                @foreach ($activeCharacters as $character)
                                    <option value="{{ $character->id }}"
                                            @if(old('team_second', $team->team_second_id ?? '') == $character->id) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('team_second')"/>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="team_members" :value="__('Team Members')"/>
                        <x-select id="team_members" name="team_members[]" class="mt-1 block w-full" multiple size="12">
                            @foreach ($activeCharacters as $character)
                                <option value="{{ $character->id }}"
                                        @if (!empty($team) && in_array($character->id, $team->character_ids)) selected @endif>{{ $character->list_name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('team_members')"/>
                        <p class="text-xs">
                            {{ __('Press Ctrl (or Cmd on Mac) to select/de-select additional characters.') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
