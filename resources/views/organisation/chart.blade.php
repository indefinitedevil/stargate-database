<x-app-layout>
    <x-slot name="title">{{ __('Organisation Chart') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Organisation Chart') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            {{ __('This represents the current organisation chart of the Stargate Expeditionary Force.') }}
            {{ __('While this is representative as the organisation as a whole, changes may occur while on deployment as a result of deployment needs and available personnel.') }}
        </div>
    </div>

    @foreach ($divisions as $division)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $division->name }}</h2>
            <div>
                @if ($division->division_head)
                    <p>
                        <strong>{{ __(strtolower($division->name) == 'command' ? 'SEF 1IC' : 'Division Head') }}:</strong>
                        {{ $division->division_head->rank }}
                        {{ $division->division_head->listName }}
                    </p>
                @endif
                @if ($division->division_second)
                    <p>
                        <strong>{{ __(strtolower($division->name) == 'command' ? 'SEF 2IC' : 'Division Second') }}:</strong>
                        {{ $division->division_second->rank }}
                        {{ $division->division_second->listName }}
                    </p>
                @endif
                @if ($division->division_staff)
                    <p>
                        <strong>{{ __('Staff Officer') }}:</strong>
                        {{ $division->division_staff->rank }}
                        {{ $division->division_staff->listName }}
                    </p>
                @endif
            </div>
            <div class="mt-4 sm:grid sm:grid-cols-2 gap-4">
                @foreach($division->departments as $department)
                    <div>
                        <h3 class="text-lg font-semibold">{{ $department->name }}</h3>
                        @if ($department->department_head)
                            <p>
                                <strong>{{ __('Department Lead') }}:</strong>
                                {{ $department->department_head->rank }}
                                {{ $department->department_head->listName }}
                            </p>
                        @endif
                        @if ($department->characters->count())
                            <p><strong>{{ __('Members:') }}</strong></p>
                            <ul class="list-disc list-inside pl-4">
                                @foreach ($department->departmentSpecialists as $character)
                                    <li>
                                        {{ $character->rank }} {{ $character->listName }}
                                    </li>
                                @endforeach
                                @foreach ($department->characters as $character)
                                    @if (0 == $character->pivot->position)
                                        <li>
                                            {{ $character->rank }} {{ $character->listName }}
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
</x-app-layout>
