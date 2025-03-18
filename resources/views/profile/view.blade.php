<x-app-layout>
    <x-slot name="title">{{ $user->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $user->name }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="max-w-xl">
            @include('profile.partials.view-profile-information-form')
        </div>
    </div>

    @can('view all characters')
        <div
            class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <div class="max-w-xl">
                <h3 class="text-lg font-semibold">{{ __('Characters') }}</h3>
                @include('characters.partials.index', ['characters' => $user->characters->sortBy('name')])
            </div>
        </div>
    @endcan
</x-app-layout>
