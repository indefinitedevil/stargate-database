<x-app-layout>
    <x-slot name="title">{{ __('Organisation Chart') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Organisation Chart') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            {{ __('This represents the current organisation chart of the Stargate Expeditionary Force.') }}
            {{ __('While this is representative as the organisation as a whole, changes may occur while on deployment as a result of deployment needs and available personnel.') }}
        </div>
    </div>

    @foreach ($divisions as $division)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $division->name }}</h2>
            @can('edit departments')
                <a class="underline ms-6" href="{{ route('divisions.edit', $division) }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                    {{ __('Edit') }}
                </a>
            @endcan
            <div>
                @if ($division->division_head)
                    <p>
                        <strong>{{ __(strtolower($division->name) == 'command' ? 'SEF 1IC' : 'Division Head') }}
                            :</strong>
                        {{ $division->division_head->rank }}
                        {{ $division->division_head->listName }}
                        @if ($division->division_head->pronouns)({{ $division->division_head->pronouns }})@endif
                        @can('edit all characters')
                            <a class="underline ms-6" href="{{ route('characters.edit', $division->division_head) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                    </p>
                @endif
                @if ($division->division_second)
                    <p>
                        <strong>{{ __(strtolower($division->name) == 'command' ? 'SEF 2IC' : 'Division Second') }}
                            :</strong>
                        {{ $division->division_second->rank }}
                        {{ $division->division_second->listName }}
                        @if ($division->division_second->pronouns)({{ $division->division_second->pronouns }})@endif
                        @can('edit all characters')
                            <a class="underline ms-6" href="{{ route('characters.edit', $division->division_second) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                    </p>
                @endif
                @if ($division->division_staff)
                    <p>
                        <strong>{{ __('Staff Officer') }}:</strong>
                        {{ $division->division_staff->rank }}
                        {{ $division->division_staff->listName }}
                        @if ($division->division_staff->pronouns)({{ $division->division_staff->pronouns }})@endif
                        @can('edit all characters')
                            <a class="underline ms-6" href="{{ route('characters.edit', $division->division_staff) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                    </p>
                @endif
            </div>
            <div class="mt-4 sm:grid sm:grid-cols-2 gap-4">
                @foreach($division->departments as $department)
                    <div>
                        <h3 class="text-lg font-semibold">{{ $department->name }}</h3>
                        @can('edit departments')
                            <a class="underline ms-6"
                               href="{{ route('departments.edit', $department) }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                {{ __('Edit') }}
                            </a>
                        @endcan
                        @if ($department->department_head)
                            <p>
                                <strong>{{ __('Department Lead') }}:</strong>
                                {{ $department->department_head->rank }}
                                {{ $department->department_head->listName }}
                                @if ($department->department_head->pronouns)({{ $department->department_head->pronouns }})@endif
                                @can('edit all characters')
                                    <a class="underline ms-6" href="{{ route('characters.edit', $department->department_head) }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        {{ __('Edit') }}
                                    </a>
                                @endcan
                            </p>
                        @endif
                        @if ($department->characters->count())
                            <p><strong>{{ __('Members:') }}</strong></p>
                            <ul class="list-disc list-inside pl-4">
                                @foreach ($department->departmentSpecialists as $character)
                                    <li>
                                        {{ $character->rank }} {{ $character->listName }}
                                        @if ($character->pronouns)({{ $character->pronouns }})@endif
                                        @can('edit all characters')
                                            <a class="underline ms-6" href="{{ route('characters.edit', $character) }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                    </li>
                                @endforeach
                                @foreach ($department->characters as $character)
                                    @if (0 == $character->pivot->position)
                                        <li>
                                            {{ $character->rank }} {{ $character->listName }}
                                            @if ($character->pronouns)({{ $character->pronouns }})@endif
                                            @can('edit all characters')
                                                <a class="underline ms-6"
                                                   href="{{ route('characters.edit', $character) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                            @endcan
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    @if (count($unassignedCharacters) > 0)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Unassigned characters') }}</h2>
            <ul class="list-disc list-inside pl-4">
                @foreach ($unassignedCharacters as $character)
                    <li>
                        {{ $character->rank }} {{ $character->listName }}
                        @if ($character->pronouns)({{ $character->pronouns }})@endif
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
