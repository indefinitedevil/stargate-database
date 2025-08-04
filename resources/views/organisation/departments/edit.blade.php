@php
    use App\Models\Character;
    $title = empty($department->id) ? __('Create department') : sprintf(__('Edit department: %s'), $department->name);
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
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf
                @if (!empty($department->id))
                    <input type="hidden" name="id" value="{{ $department->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-2 space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Department Name')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="old('name', $department->name ?? '')" required autofocus/>
                            <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')"/>
                            <x-textarea id="description" name="description" rows="4"
                                        class="mt-1 block w-full">{{ $department->description ?? '' }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                            <p class="text-xs mt-1">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        </div>
                    </div>

                    <div class="col-span-2 space-y-6">
                        <div>
                            <x-input-label for="department_head" :value="__('Department Head')"/>
                            <x-select id="department_head" name="department_head" class="mt-1 block w-full">
                                <option value="">{{ __('Select a department head') }}</option>
                                @foreach (Character::getActiveCharacters() as $character)
                                    <option value="{{ $character->id }}"
                                            @if(old('department_head', $department->department_head_id ?? '') == $character->id) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('department_head')"/>
                        </div>

                        <div>
                            <x-input-label for="department_specialists" :value="__('Department Specialists')"/>
                            <x-select id="department_specialists" name="department_specialists[]" class="mt-1 block w-full" multiple size="6">
                                @foreach (Character::getActiveCharacters() as $character)
                                    <option value="{{ $character->id }}"
                                            @if (!empty($department) && in_array($character->id, $department->department_specialist_ids)) selected @endif>{{ $character->list_name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('department_specialists')"/>
                            <p class="text-xs">
                                {{ __('Press Ctrl to select/de-select additional characters.') }}
                            </p>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <x-input-label for="department_members" :value="__('Department Members')"/>
                        <x-select id="department_members" name="department_members[]" class="mt-1 block w-full" multiple size="12">
                            @foreach (Character::getActiveCharacters() as $character)
                                <option value="{{ $character->id }}"
                                        @if (!empty($department) && in_array($character->id, $department->character_ids)) selected @endif>{{ $character->list_name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('department_members')"/>
                        <p class="text-xs">
                            {{ __('Press Ctrl to select/de-select additional characters.') }}
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
