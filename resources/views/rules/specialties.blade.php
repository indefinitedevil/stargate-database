<x-app-layout>
    <x-slot name="title">{{ __('Specialties') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Specialties') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300 space-y-6">
        <p>
            {{ __('This is a list of all specialties available within the character database.') }}
            {{ __('This is a more comprehensive list than is covered within the rulebook and will cover all new specialties added by either the Plot Coordinator or the System Referees.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    @foreach ($specialtyTypes as $specialtyType)
        @php $rowCount = floor(count($specialties[$specialtyType->id]) / 10) + 1; @endphp
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300 row-span-{{ $rowCount }}">
            <h3 class="text-xl font-semibold">{!! sprintf('%s Skills', $specialtyType->name) !!}</h3>
                <ul class="list-disc list-inside">
                @foreach($specialties[$specialtyType->id] as $specialty)
                    <li>
                        {{ $specialty->name }}
                    </li>
                @endforeach
                </ul>
        </div>
    @endforeach
    </div>
</x-app-layout>
