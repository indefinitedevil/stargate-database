@php
    use App\Models\Character;
    use App\Models\Skill;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Check Downtime Processing') }}</x-slot>
    <x-slot name="header">
        @if(!$downtime->open && now()->gt($downtime->end_time) && !$downtime->processed)
            <a href="{{ route('plotco.downtimes.process', ['downtimeId' => $downtime]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
               onclick="return confirm('{{ __('Are you sure you want to process this downtime?') }}')"
            >{{ __('Process') }}</a>
        @elseif ($downtime->open)
            <a href="{{ route('plotco.downtimes.remind', ['downtimeId' => $downtime]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
               onclick="return confirm('{{ __('Are you sure you want to send a reminder?') }}')"
            ><i class="fa-solid fa-envelope"></i> {{ __('Remind') }}</a>
        @endif
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __($downtime->event_id ? 'Check Downtime Processing: :name (:event)' : 'Check Downtime Processing: :name', ['name' => $downtime->name, 'event' => $downtime->event->name ?? '']) }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            @php
                extract($downtime->preprocess());
                $skills = $characters = [];
            @endphp
            <h3 class="text-xl font-semibold">{{ __('Teaching and training') }}</h3>
            <div class="sm:grid sm:grid-cols-3 gap-6">
                @foreach($taughtSkills as $skillId => $teachers)
                    @php
                        if (empty($skills[$skillId])) {
                            $skills[$skillId] = Skill::find($skillId);
                        }
                        $trainedCharacters = [];
                        $teacherIds = array_keys($teachers);
                    @endphp
                    <div>
                        <p class="text-lg font-semibold">{{ $skills[$skillId]->name }}</p>
                        <ul class="list-disc list-inside">
                            @foreach($teachers as $teacherId => $action)
                                @php
                                    if (empty($characters[$teacherId])) {
                                        $characters[$teacherId] = Character::find($teacherId);
                                    }
                                @endphp
                                <li>
                                    {{ __('Taught by :name', ['name' => $characters[$teacherId]->listName]) }}
                                    ({{ __('+1 Vigor at next event') }})
                                </li>
                            @endforeach
                            @if (!empty($trainedSkills[$skillId]))
                                @foreach($trainedSkills[$skillId] as $characterId => $actions)
                                    @php
                                        if (empty($characters[$characterId])) {
                                            $characters[$characterId] = Character::find($characterId);
                                        }
                                        $trainedCharacters[] = $characterId;
                                    @endphp
                                    <li>
                                        {{ trans_choice('Trained by :name (:months month)|Trained by :name (:months months)', count($actions), ['name' => $characters[$characterId]->listName, 'months' => count($actions)]) }}
                                        @if (!in_array($characterId, $teacherIds))
                                            ({{ __('+1 month from course') }})
                                        @endif
                                    </li>
                                @endforeach
                                @php unset($trainedSkills[$skillId]); @endphp
                            @endif
                            @foreach ($skills[$skillId]->subskills as $subSkill)
                                @if (!empty($trainedSkills[$subSkill->id]))
                                    @foreach($trainedSkills[$subSkill->id] as $characterId => $actions)
                                        @php
                                            if (empty($characters[$characterId])) {
                                                $characters[$characterId] = Character::find($characterId);
                                            }
                                        @endphp
                                        <li>
                                            {{ trans_choice(':skill trained by :name (:months month)|:skill trained by :name (:months months)', count($actions), ['skill' => $subSkill->name, 'name' => $characters[$characterId]->listName, 'months' => count($actions)]) }}
                                            @if (!in_array($characterId, $teacherIds) && !in_array($characterId, $trainedCharacters))
                                                ({{ __('+1 month from course') }})
                                            @endif
                                        </li>
                                        @php
                                            $trainedCharacters[] = $characterId;
                                        @endphp
                                    @endforeach
                                    @php unset($trainedSkills[$subSkill->id]); @endphp
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                @foreach($trainedSkills as $skillId => $learners)
                    @php
                        if (empty($skills[$skillId])) {
                            $skills[$skillId] = Skill::find($skillId);
                        }
                    @endphp
                    <div>
                        <p class="text-lg font-semibold">{{ $skills[$skillId]->name }}</p>
                        <ul class="list-disc list-inside">
                            @foreach($learners as $characterId => $actions)
                                @php
                                    if (empty($characters[$characterId])) {
                                        $characters[$characterId] = Character::find($characterId);
                                    }
                                @endphp
                                <li>
                                    {{ trans_choice('Trained by :name (:months month)|Trained by :name (:months months)', count($actions), ['name' => $characters[$characterId]->listName, 'months' => count($actions)]) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            @if (!empty($requiredUpkeepSkills))
                <h3 class="text-xl font-semibold">{{ __('Upkeep') }}</h3>
                <div class="sm:grid sm:grid-cols-3 gap-6">
                    @foreach($requiredUpkeepSkills as $skillId => $requiredUpkeepCharacters)
                        @php
                            if (empty($skills[$skillId])) {
                                $skills[$skillId] = Skill::find($skillId);
                            }
                        @endphp
                        <div>
                            <p class="text-lg font-semibold">{{ $skills[$skillId]->name }}</p>
                            <ul class="list-disc list-inside">
                                @foreach($requiredUpkeepCharacters as $characterId)
                                    @php
                                        if (empty($characters[$characterId])) {
                                            $characters[$characterId] = Character::find($characterId);
                                        }
                                    @endphp
                                    <li>
                                        @if (!empty($upkeepMaintenance[$skillId]) && in_array($characterId, $upkeepMaintenance[$skillId]))
                                            {{ __('Maintained by :name', ['name' => $characters[$characterId]->listName]) }}
                                        @else
                                            {{ __('Required by :name - WILL BE LOST', ['name' => $characters[$characterId]->listName]) }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if ($downtime->missions->count())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <h3 class="text-xl font-semibold">{{ __('Missions') }}</h3>
                <div class="sm:grid sm:grid-cols-3 gap-6">
                </div>
            </div>
        </div>
    @endif

    @if ($downtime->researchActions()->count())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <h3 class="text-xl font-semibold">{{ __('Research Projects') }}</h3>
                <div class="sm:grid sm:grid-cols-3 gap-6">
                    @foreach ($downtime->researchProjects as $project)
                        <div>
                            <p class="text-lg font-semibold">{{ $project->name }}</p>
                            <ul class="list-disc list-inside">
                                @if ($project->researchActions()->where('downtime_id', $downtime->id)->count())
                                    @foreach($project->researchActions()->where('downtime_id', $downtime->id)->get() as $action)
                                        <li>
                                            {{ __('Researcher: :name', ['name' => $action->character->listName]) }}
                                        </li>
                                    @endforeach
                                @endif
                                @if ($project->subjectActions()->where('downtime_id', $downtime->id)->count())
                                    @foreach($project->subjectActions()->where('downtime_id', $downtime->id)->get() as $action)
                                        <li>
                                            {{ __('Subject: :name', ['name' => $action->character->listName]) }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if ($downtime->personalActions()->count())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <h3 class="text-xl font-semibold">{{ __('Personal Actions') }}</h3>
                <div class="sm:grid sm:grid-cols-3 gap-6">
                    @foreach($downtime->personalActions() as $action)
                        <div>
                            <p class="text-lg font-semibold">{{ $action->character->listName }}</p>
                            <ul class="list-disc list-inside">
                                <li>{{ $action->notes }}</li>
                                @if (!empty($action->response))
                                    <li>{{ __('Response: :response', ['response' => $action->response]) }}</li>
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
