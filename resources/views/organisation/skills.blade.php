<x-app-layout>
    <x-slot name="title">{{ __('Skill coverage') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skill coverage') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300 space-y-6">
        <p>{{ __('This has been made available to allow players to see where there may be niches they could fill with new characters.') }}</p>
        <p>
            {{ __('Only characters that are "Approved" or "Played" will be included in the below counts.') }}
            {{ __('This does include secondary characters and other characters who may not have been played yet, and characters who haven\'t been played recently.') }}
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    @foreach ($skillCategories as $category)
        @php $rowCount = floor($category->cleanSkills->count() / 10) + 1; @endphp
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300 row-span-{{ $rowCount }}">
            <h3 class="text-xl font-semibold">{!! sprintf('%s Skills', $category->name) !!}</h3>
                <ul class="list-disc list-inside">
                @foreach($category->cleanSkills as $skill)
                    <li>
                        @php
                            $characterSkills = $skill->characterSkills
                                ->where('completed', true)
                                ->whereIn('character_id', $validCharacters)
                                ->groupBy('character_id');
                        @endphp
                        {{ sprintf('%s (%d)', $skill->name, count($characterSkills)) }}
                    </li>
                @endforeach
                </ul>
        </div>
    @endforeach
    </div>
</x-app-layout>
