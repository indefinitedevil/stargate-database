@if($downtime->isOpen() && $downtime->trainingCourses->count() > 0)
    <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <h3 class="text-lg font-semibold">{{ __('Training Courses') }}</h3>
            <div class="space-y-2">
                <p>{{ __('The following training courses are being run this cycle:') }}</p>
                <ul class="list-inside list-disc">
                    @foreach($downtime->trainingCourses as $trainingCourse)
                        <li>
                            {{ $trainingCourse->characterSkill->name }}
                            @if ($trainingCourse->character_id == $character->id)
                                ({{ trans_choice('You are teaching :count trainee|You are teaching :count trainees', $downtime->countTrainees($trainingCourse->characterSkill->skill_id), ['count' => $downtime->countTrainees($trainingCourse->characterSkill->skill_id)]) }})
                            @endif
                            @if ($trainingCourse->characterSkill->skill->subSkills->count() > 0)
                                <ul class="list-inside list-disc ml-4">
                                    @foreach($trainingCourse->characterSkill->skill->subSkills as $subSkill)
                                        <li>
                                            {{ __('Can be used for :skill', ['skill' => $subSkill->name]) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <p>{{ __('Training courses provide an additional month of training beyond your own actions. Benefits from training courses will be applied after the downtime is processed.') }}</p>
            </div>
        </div>
    </div>
@endif
