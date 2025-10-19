<x-app-layout>
    <x-slot name="title">{{ __('Teams') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>
    @can('edit teams')
        <x-slot name="sidebar2">
            <x-dropdown-link href="{{ route('teams.create') }}">
                <i class="fa-solid fa-plus min-w-8"></i>
                {{ __('Create') }}
            </x-dropdown-link>
        </x-slot>
    @endcan

    <div
        class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300 sm:grid grid-cols-2">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Permanent Teams') }}</h2>
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
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Event Teams') }}</h2>
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
    @if (count($unassignedCharacters) > 0)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ 'Unassigned characters' }}</h2>
            <ul class="list-disc list-inside pl-4">
                @foreach ($unassignedCharacters as $character)
                    <li>
                        {{ $character->rank }} {{ $character->listName }}
                        @can('edit all characters')
                            <a class="underline ms-6" href="{{ route('characters.edit', $character) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-app-layout>
