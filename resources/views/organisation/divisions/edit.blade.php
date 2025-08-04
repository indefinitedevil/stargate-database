@php
    use App\Models\Character;
    $title = empty($division->id) ? __('Create division') : sprintf(__('Edit division: %s'), $division->name);
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
            <form method="POST" action="{{ route('divisions.store') }}">
                @csrf
                @if (!empty($division->id))
                    <input type="hidden" name="id" value="{{ $division->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-2 space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Division Name')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="old('name', $division->name ?? '')" required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')"/>
                            <x-textarea id="description" name="description" rows="4"
                                        class="mt-1 block w-full">{{ $division->description ?? '' }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                            <p class="text-xs mt-1">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        </div>
                    </div>

                    <div class="col-span-2 space-y-6">
                        <div>
                            <x-input-label for="division_head" :value="__('Division Head')"/>
                            <x-select id="division_head" name="division_head" class="mt-1 block w-full">
                                <option value="">{{ __('Select a division head') }}</option>
                                @foreach (Character::getActiveCharacters() as $character)
                                    <option value="{{ $character->id }}"
                                            @if(old('division_head', $division->division_head_id ?? '') == $character->id) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('division_head')"/>
                        </div>
                        <div>
                            <x-input-label for="division_second" :value="__('Division 2IC')"/>
                            <x-select id="division_second" name="division_second" class="mt-1 block w-full">
                                <option value="">{{ __('Select a division second') }}</option>
                                @foreach (Character::getActiveCharacters() as $character)
                                    <option value="{{ $character->id }}"
                                            @if(old('division_second', $division->division_second_id ?? '') == $character->id) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('division_second')"/>
                        </div>
                        <div>
                            <x-input-label for="division_staff" :value="__('Division Staff Officer')"/>
                            <x-select id="division_staff" name="division_staff" class="mt-1 block w-full">
                                <option value="">{{ __('Select a division staff officer') }}</option>
                                @foreach (Character::getActiveCharacters() as $character)
                                    <option value="{{ $character->id }}"
                                            @if(old('division_staff', $division->division_staff_id ?? '') == $character->id) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('division_staff')"/>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
