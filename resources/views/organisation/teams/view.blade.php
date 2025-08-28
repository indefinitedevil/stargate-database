<x-app-layout>
    <x-slot name="title">{{ __('Team :team', ['team' => $team->name]) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Team :team', ['team' => $team->name]) }}
        </h2>
    </x-slot>
    @can('edit teams')
        <x-slot name="sidebar2">
            <x-dropdown-link href="{{ route('teams.edit', ['teamId' => $team]) }}">
                <i class="fa-solid fa-pen min-w-8"></i>
                {{ __('Edit') }}
            </x-dropdown-link>
        </x-slot>
    @endcan

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="space-y-2">
            <p>
                <strong>{{ __('Name') }}:</strong>
                {{ $team->name }}
            </p>
            <p>
                <strong>{{ __('Description') }}:</strong>
                {{ $team->description }}
            </p>
            <div>
                <strong>{{ __('Team Members') }}</strong>
                @include('organisation.partials.team')
            </div>
        </div>
    </div>
</x-app-layout>
