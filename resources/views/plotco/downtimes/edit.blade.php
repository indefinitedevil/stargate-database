@php
    $title = empty($downtime->name) ? __('Create downtime') : sprintf(__('Edit downtime: %s'), $downtime->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('plotco.downtimes.store') }}">
                        @csrf
                        @if (!empty($downtime))
                            <input type="hidden" name="id" value="{{ $downtime->id }}">
                        @endif
                        <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                            <div class="col-span-6">
                                <x-input-label for="name" :value="__('Downtime Name')"/>
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                              :value="old('name', $downtime->name ?? '')" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="start_time" :value="__('Start Time')"/>
                                <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full"
                                              :value="old('start_time', $downtime->start_time ? $downtime->start_time->format('Y-m-d H:i:s') : '')" required/>
                                <x-input-error class="mt-2" :messages="$errors->get('start_time')"/>
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="end_time" :value="__('End Time')"/>
                                <x-text-input id="end_time" name="end_time" type="datetime-local" class="mt-1 block w-full"
                                              :value="old('end_time', $downtime->end_time ? $downtime->end_time->format('Y-m-d H:i:s') : '')" required/>
                                <x-input-error class="mt-2" :messages="$errors->get('end_time')"/>
                            </div>

                            <div class="col-span-2"></div>

                            <div>
                                <x-input-label for="development_actions" :value="__('Development Actions')"/>
                                <x-text-input id="development_actions" name="development_actions" type="number" class="mt-1 block w-full"
                                              :value="old('development_actions', $downtime->development_actions ?? 3)" required/>
                                <x-input-error class="mt-2" :messages="$errors->get('development_actions')"/>
                            </div>

                            <div>
                                <x-input-label for="research_actions" :value="__('Research Actions')"/>
                                <x-text-input id="research_actions" name="research_actions" type="number" class="mt-1 block w-full"
                                              :value="old('research_actions', $downtime->research_actions ?? 3)" required/>
                                <x-input-error class="mt-2" :messages="$errors->get('research_actions')"/>
                            </div>

                            <div>
                                <x-input-label for="other_actions" :value="__('Other Actions')"/>
                                <x-text-input id="other_actions" name="other_actions" type="number" class="mt-1 block w-full"
                                              :value="old('other_actions', $downtime->other_actions ?? 1)" required/>
                                <x-input-error class="mt-2" :messages="$errors->get('other_actions')"/>
                            </div>

                            <div class="col-span-3"></div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
