<x-app-layout>
    <x-slot name="title">{{ __('Teams') }}</x-slot>
    <x-slot name="header">
        @can('edit teams')
            <x-link-button href="{{ route('teams.create') }}"
                           class="float-right">{{ __('Create') }}</x-link-button>
        @endcan
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <div
        class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300 sm:grid grid-cols-2">
        <div>
            <h3 class="text-xl">{{ __('Permanent Teams') }}</h3>
            <ul class="list-disc list-inside space-y-2">
                @if ($permanentTeams->isEmpty())
                    <li>{{ __('No teams found') }}</li>
                @else
                    @foreach ($permanentTeams as $team)
                        <li>
                            <strong><a href="{{ $team->getViewRoute() }}"
                                       class="underline">{{ $team->name }}</a></strong>
                            @can('edit teams')
                                <a class="underline ms-6" href="{{ route('teams.edit', $team) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    {{ __('Edit') }}
                                </a>
                            @endcan
                            @include('organisation.partials.team')
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <div>
            <h3 class="text-xl">{{ __('Event Teams') }}</h3>
            <ul class="list-disc list-inside space-y-2">
                @if ($eventTeams->isEmpty())
                    <li>{{ __('No teams found') }}</li>
                @else
                    @foreach ($eventTeams as $team)
                        <li>
                            <strong><a href="{{ $team->getViewRoute() }}"
                                       class="underline">{{ $team->name }}</a></strong>
                            @can('edit teams')
                                <a class="underline ms-6" href="{{ route('teams.edit', $team) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    {{ __('Edit') }}
                                </a>
                            @endcan
                            @include('organisation.partials.team')
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</x-app-layout>
