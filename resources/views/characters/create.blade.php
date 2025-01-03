@php
    use App\Models\Background;
    use App\Models\Status;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Create character') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create character') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="status_id" value="{{ Status::NEW }}">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')"/>
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                              required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                            </div>

                            <div>
                                <x-input-label for="former_rank" :value="__('Former Rank (if applicable)')"/>
                                <x-text-input id="former_rank" name="former_rank" type="text" class="mt-1 block w-full"/>
                                <x-input-error class="mt-2" :messages="$errors->get('former_rank')"/>
                            </div>

                            <div>
                                <x-input-label for="background">Background</x-input-label>
                                <x-select id="background" name="background_id" class="mt-1 block w-full" required>
                                    @foreach(Background::all() as $background)
                                        <option value="{{ $background->id }}">{{ $background->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>

                            @include('characters.partials.event-attendance')

                            <div>
                                <x-input-label for="history" :value="__('History')"/>
                                <x-textarea id="history" name="history" rows="12"
                                            class="mt-1 block w-full"></x-textarea>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Create') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <p>{{ __('Once you\'ve created your character, you can move onto adding skills. You can come back and edit these details afterwards if you need to.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
