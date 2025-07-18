@php
    use App\Models\Event;
    use App\Models\Skill;
    use App\Models\SkillCategory;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Skill breakdown') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skill breakdown') }}
        </h2>
    </x-slot>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <p>
                    {{ __('Only characters that are "Approved" or "Played" will be included in the below counts.') }}
                    {{ __('There are :count characters that match.', ['count' => count($validCharacters)]) }}
                </p>
                <div class="flex flex-wrap">
                    <x-link-button href="{{ route('plotco.skills') }}"
                                   :primary="!isset($_GET['event'])" class="mr-3 mt-4"
                    >{{ __('All') }}</x-link-button>
                    @foreach (Event::where('end_date', '>', date('Y-m-d'))->get() as $event)
                        <x-link-button href="{{ route('plotco.skills', ['event' => $event->id]) }}"
                                       :primary="isset($_GET['event']) && $event->id == $_GET['event']"
                                       class="mr-3 mt-4"
                        >{{ $event->name }}</x-link-button>
                    @endforeach
                </div>
            </div>
            @foreach (SkillCategory::all() as $category)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <h3 class="text-xl font-semibold">{!! sprintf('%s Skills', $category->name) !!}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        @foreach($category->skills->where('id', '!=', Skill::ADDITIONAL_AA_SPEC)->sortBy('name') as $skill)
                            <div class="mt-1">
                                @php
                                    $characterSkills = $skill->characterSkills
                                        ->where('completed', true)
                                        ->whereIn('character_id', $validCharacters)
                                        ->groupBy('character_id');
                                @endphp
                                <h4 class="text-lg font-semibold">{{ sprintf('%s (%d)', $skill->name, count($characterSkills)) }}</h4>
                                <ul>
                                    @foreach($characterSkills as $characterSkillCollection)
                                        @php $characterSkill = $characterSkillCollection->first(); @endphp
                                        <li>
                                            <a class="underline"
                                               href="{{ route('characters.view', ['characterId' => $characterSkill->character, 'characterName' => Str::slug($characterSkill->character->name)]) }}">
                                                {{ $characterSkill->character->listName }}</a>
                                            @if ($characterSkill->skill->repeatable)
                                                ({{ $characterSkill->level }})
                                            @endif
                                            @if ($characterSkill->skill->specialties)
                                                <ul class="list-inside list-disc">
                                                    @foreach($characterSkill->allSpecialties as $specialty)
                                                        <li>{{ $specialty->name }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
</x-app-layout>
