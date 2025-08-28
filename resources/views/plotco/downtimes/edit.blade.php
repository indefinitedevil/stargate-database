@php
    use App\Models\Event;
    $title = empty($downtime->id) ? __('Create downtime') : sprintf(__('Edit downtime: %s'), $downtime->name);
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
            <form method="POST" action="{{ route('plotco.downtimes.store') }}">
                @csrf
                @if (!empty($downtime->id))
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
                        <x-text-input id="start_time" name="start_time" type="datetime-local"
                                      class="mt-1 block w-full"
                                      :value="old('start_time', $downtime->start_time ? format_datetime($downtime->start_time, 'Y-m-d H:i:s') : '')"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('start_time')"/>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="end_time" :value="__('End Time')"/>
                        <x-text-input id="end_time" name="end_time" type="datetime-local"
                                      class="mt-1 block w-full"
                                      :value="old('end_time', $downtime->end_time ? format_datetime($downtime->end_time, 'Y-m-d H:i:s') : '')"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('end_time')"/>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="event_id" :value="__('Event (optional)')"/>
                        <x-select id="event_id" name="event_id" class="mt-1 block w-full">
                            <option value="">{{ __('Select an event') }}</option>
                            @foreach(Event::all() as $event)
                                <option value="{{ $event->id }}"
                                        @if(old('event_id', $downtime->event_id ?? '') == $event->id) selected @endif>{{ $event->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('event_id')"/>
                    </div>

                    <div>
                        <x-input-label for="development_actions" :value="__('Development Actions')"/>
                        <x-text-input id="development_actions" name="development_actions" type="number"
                                      class="mt-1 block w-full"
                                      :value="old('development_actions', $downtime->development_actions ?? 3)"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('development_actions')"/>
                    </div>

                    <div>
                        <x-input-label for="research_actions" :value="__('Research Actions')"/>
                        <x-text-input id="research_actions" name="research_actions" type="number"
                                      class="mt-1 block w-full"
                                      :value="old('research_actions', $downtime->research_actions ?? 3)"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('research_actions')"/>
                    </div>

                    <div>
                        <x-input-label for="experiment_actions" :value="__('Research Subject Actions')"/>
                        <x-text-input id="experiment_actions" name="experiment_actions" type="number"
                                      class="mt-1 block w-full"
                                      :value="old('experiment_actions', $downtime->experiment_actions ?? 1)"
                                      required/>
                        <x-input-error class="mt-2" :messages="$errors->get('experiment_actions')"/>
                    </div>

                    <div>
                        <x-input-label for="other_actions" :value="__('Personal Actions')"/>
                        <x-text-input id="other_actions" name="other_actions" type="number"
                                      class="mt-1 block w-full"
                                      :value="old('other_actions', $downtime->other_actions ?? 1)" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('other_actions')"/>
                    </div>

                    <div class="col-span-3">
                        <x-input-label for="response" :value="__('Response')"/>
                        <x-textarea id="response" name="response" rows="6" maxlength="65000"
                                    class="mt-1 block w-full">{{ $downtime->response ?? '' }}</x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('response')"/>
                        <p class="text-xs mt-1">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        <p class="text-xs mt-1">{{ __('This field is not visible to players until the downtime is processed.') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
