@php
    use App\Models\Skill;
    use App\Models\SkillCategory;
    use App\Models\Status;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Skill breakdown') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skill breakdown') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @foreach (SkillCategory::all() as $category)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <h3 class="text-xl font-semibold">{!! sprintf('%s Skills', $category->name) !!}</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($category->skills->where('id', '!=', Skill::ADDITIONAL_AA_SPEC)->sortBy('name') as $skill)
                            <div class="mt-1">
                                @php
                                    $characterSkills = $skill->characterSkills->where('completed', true);
                                    $validCharacterSkills = [];
                                    foreach ($characterSkills as $characterSkill) {
                                        if (in_array($characterSkill->character->status_id, [Status::APPROVED, Status::PLAYED])) {
                                            $validCharacterSkills[$characterSkill->skill->id] = $characterSkill;
                                        }
                                    }
                                @endphp
                                <h4 class="text-lg font-semibold">{{ sprintf('%s (%d)', $skill->name, count($validCharacterSkills)) }}</h4>
                                <ul>
                                    @foreach($validCharacterSkills as $characterSkill)
                                        <li>
                                            {{ $characterSkill->character->name }}
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
        </div>
    </div>
</x-app-layout>
