@php
    use App\Models\Background;
    use App\Models\Status;
    $fieldClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full';
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Edit character') }}</x-slot>
    <x-slot name="header">
        @include('characters.partials.actions', ['character' => $character])
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ sprintf(__('Edit character: %s'), $character->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @include('characters.partials.approval', ['character' => $character])
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $character->user_id }}">
                        <input type="hidden" name="id" value="{{$character->id }}">
                        <input type="hidden" name="status_id" value="{{ $character->status_id }}">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')"/>
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                              :value="old('name', $character->name)" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                            </div>

                            <div>
                                <x-input-label for="former_rank" :value="__('Former Rank (if applicable)')"/>
                                <x-text-input id="former_rank" name="former_rank" type="text" class="mt-1 block w-full"
                                              :value="old('former_rank', $character->former_rank)"
                                              :disabled="Status::NEW != $character->status_id"/>
                                <x-input-error class="mt-2" :messages="$errors->get('former_rank')"/>
                            </div>

                            <div>
                                <x-input-label for="background" :value="__('Background')"/>
                                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                                @if (Status::NEW === $character->status_id)
                                    <select id="background" name="background_id" class="{{ $fieldClass }}" required>
                                        @foreach(Background::all() as $background)
                                            <option value="{{ $background->id }}"
                                                    @if($background->id === $character->background_id) selected @endif >
                                                {{ $background->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <x-text-input id="background" name="background" type="text" class="mt-1 block w-full"
                                                  :value="$character->background->name" disabled/>
                                    <input type="hidden" name="background_id" value="{{ $character->background_id }}">
                                @endif
                            </div>

                            <div>
                                <x-input-label for="history" :value="__('History')"/>
                                <textarea id="history" class="{{ $fieldClass }}" rows="12"
                                          name="history">{{ $character->history }}</textarea>
                            </div>

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
