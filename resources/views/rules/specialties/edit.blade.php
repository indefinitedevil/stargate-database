@php
    $title = empty($specialty->id) ? __('Create skill specialty') : sprintf(__('Edit skill specialty: %s'), $specialty->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>
    @if (!empty($specialty))
        <x-slot name="sidebar2">
            @can ('delete skill specialty')
                <form method="POST" action="{{ route('rules.specialties.delete', ['specialtyId' => $specialty]) }}"
                      onsubmit="return confirm('{{ __('Are you sure you want to delete this skill specialty?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                        <i class="fa-solid fa-trash min-w-8"></i>
                        {{ __('Delete') }}
                    </button>
                </form>
            @endcan
        </x-slot>
    @endif

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <form method="POST" action="{{ route('rules.specialties.store') }}">
                @csrf
                @if (!empty($specialty->id))
                    <input type="hidden" name="id" value="{{ $specialty->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="sm:col-span-3">
                        <x-input-label for="name" :value="__('Name')"/>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $specialty->name ?? '')" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="specialty_type_id" :value="__('Specialty Type')"/>
                        <x-select id="specialty_type_id" name="specialty_type_id" class="mt-1 block w-full">
                            @foreach ($specialtyTypes as $specialtyType)
                                <option value="{{ $specialtyType->id }}"
                                        @if(old('specialty_type_id', $specialty->specialty_type_id ?? '') == $specialtyType->id) selected @endif>{{ $specialtyType->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('specialty_type_id')"/>
                    </div>

                    <div class="sm:col-span-3">
                        <x-input-label for="hidden" class="text-lg">
                            <input type="hidden" name="hidden" value="0"/>
                            <x-checkbox-input id="hidden"
                                              name="hidden"
                                              value="1"
                                              :checked="old('hidden', $specialty->hidden ?? false)"/>
                            {{ __('Hidden from players') }}
                        </x-input-label>
                        <x-input-error class="mt-2" :messages="$errors->get('hidden')"/>
                    </div>
                </div>
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
