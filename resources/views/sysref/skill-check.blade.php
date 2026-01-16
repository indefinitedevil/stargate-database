@php
    use App\Helpers\CharacterHelper;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Skill check') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skill check') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:px-8 sm:py-4 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <p>
            <strong>{{ __('Lowest training months on an active character:') }}</strong> {{ CharacterHelper::getLowestTrainingMonths() }}
        </p>
        <p>
            <strong>{{ __('Lowest training months on an active character who has done a downtime:') }}</strong> {{ CharacterHelper::getLowestTrainingMonthsIncludingDowntime() }}
        </p>
        <p>
            <strong>{{ __('Highest training months on an active character:') }}</strong> {{ CharacterHelper::getHighestTrainingMonths() }}
        </p>
        <p>
            <strong>{{ __('Catchup XP:') }}</strong> {{ CharacterHelper::getCatchupXP() }}
        </p>
    </div>

    @include('rules/partials/skills')
</x-app-layout>
