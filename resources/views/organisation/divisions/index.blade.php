<x-app-layout>
    <x-slot name="title">{{ __('Divisions') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Divisions') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <ul class="list-disc list-inside space-y-2">
                @if (count($divisions) == 0)
                    <li>{{ __('No divisions found') }}</li>
                @else
                    @foreach ($divisions as $division)
                        <li>
                            <strong>{{ $division->name }}</strong>
                            @can('edit departments')
                                <a class="underline ms-6" href="{{ route('divisions.edit', $division) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    {{ __('Edit') }}
                                </a>
                            @endcan
                            <ul class="list-disc list-inside space-y-2 pl-4 mt-1">
                                @if (count($division->departments) == 0)
                                    <li>{{ __('No departments found') }}</li>
                                @else
                                    @foreach ($division->departments as $department)
                                        <li>
                                            <strong>{{ $department->name }}</strong>
                                            @can('edit departments')
                                                <a class="underline ms-6"
                                                   href="{{ route('departments.edit', $department) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                    {{ __('Edit') }}
                                                </a>
                                            @endcan
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</x-app-layout>
