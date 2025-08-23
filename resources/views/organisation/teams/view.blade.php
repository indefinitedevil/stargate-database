@php use App\Models\Team; @endphp
<x-app-layout>
    <x-slot name="title">{{ __('Team :team', ['team' => $team->name]) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @can ('edit teams')
                <x-link-button href="{{ route('teams.edit', ['teamId' => $team]) }}"
                               class="float-right">{{ __('Edit') }}</x-link-button>
            @endcan
            {{ __('Team :team', ['team' => $team->name]) }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
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
