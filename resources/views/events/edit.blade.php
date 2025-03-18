@php
    $title = empty($event->id) ? __('Create event') : sprintf(__('Edit event: %s'), $event->name);
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
            <form method="POST" action="{{ route('events.store') }}">
                @csrf
                @if (!empty($event->id))
                    <input type="hidden" name="id" value="{{ $event->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-6">
                        <x-input-label for="name" :value="__('Event Name')"/>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $event->name ?? '')" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div>
                        <x-input-label for="start_date" :value="__('Start Date')"/>
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 block w-full"
                                      :value="old('start_date', $event->start_date ? $event->start_date->format('Y-m-d') : '')"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('start_date')"/>
                    </div>

                    <div>
                        <x-input-label for="end_date" :value="__('End Date')"/>
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 block w-full"
                                      :value="old('end_date', $event->end_date ? $event->end_date->format('Y-m-d') : '')"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('end_date')"/>
                    </div>

                    <div class="col-span-4">
                        <x-input-label for="location" :value="__('Location')"/>
                        <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                                      :value="old('location', $event->location ?? 'TBC')" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('location')"/>
                    </div>

                    <div class="col-span-6">
                        <x-input-label for="description" :value="__('Description')"/>
                        <x-textarea id="description" name="description" rows="12"
                                    class="mt-1 block w-full">{{ $event->description ?? 'TBC' }}</x-textarea>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
