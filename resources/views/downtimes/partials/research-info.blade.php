@if($downtime->isOpen() && $downtime->researchProjects->count() > 0)
    <div
            class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300 row-span-{{ $downtime->research_actions + $downtime->experiment_actions }}">
        <div>
            <h3 class="text-lg font-semibold">{{ __('Research Projects') }}</h3>
            <div class="space-y-2">
                <p>{{ __('The following research projects are active this cycle:') }}</p>
                <ul class="list-inside list-disc">
                    @foreach($downtime->researchProjects as $researchProject)
                        <li>
                            <a href="{{ $researchProject->getViewRoute() }}" target="_blank"
                               class="underline"><strong>{!! process_inline_markdown($researchProject->name) !!}</strong></a>
                            {{ __('(:done / :total months)', ['done' => $researchProject->researchActions()->count(), 'total' => $researchProject->months]) }}
                            (<a class="cursor-pointer underline decoration-dashed underline-offset-4" onclick="toggleVisibility('research-skills-{{ $researchProject->id }}')">{{ __('Show skills') }}</a>)
                            <p class="ml-5">{!! process_inline_markdown(__('Subject: :subject', ['subject' => $researchProject->research_subject])) !!}</p>
                            <p class="ml-5 hidden" id="research-skills-{{ $researchProject->id }}">
                                <strong>{{ __('Skills:') }}</strong>
                                @if ($researchProject->skills->count() > 0)
                                    @foreach($researchProject->skills as $skill)
                                        {!! $skill->name . ($researchProject->skillCheck($skill->id) ? ' <i class="fa-solid fa-check"></i>' : '') !!}@if (!empty($skill->specialty_type_id) && !empty($researchProject->specialties[$skill->specialty_type_id]))
                                            (@foreach($researchProject->specialties[$skill->specialty_type_id] as $specialty){!! $specialty->name . ($researchProject->specialtyCheck($specialty->id) ? ' <i class="fa-solid fa-check"></i>' : '') !!}{{ $loop->last ? ')' : ', ' }}@endforeach
                                        @endif{{ $loop->last ? '' : ', ' }}
                                    @endforeach
                                @else
                                    {{ __('None specified') }}
                                @endif
                            </p>
                            @php $researchCharacters = $researchProject->researchCharacters($downtime->id); @endphp
                            @if ($researchCharacters->count() > 0)
                                <ul class="list-inside list-disc ml-4">
                                    @foreach($researchCharacters as $researchCharacter)
                                        <li>
                                            {{ __('Researching: :researcher (:skills)', ['researcher' => $researchCharacter['character']->listName, 'skills' => $researchCharacter['skills']]) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            @php $subjectActions = $researchProject->subjectActions()->where('downtime_id', $downtime->id)->get(); @endphp
                            @if ($subjectActions->count() > 0)
                                <ul class="list-inside list-disc ml-4">
                                    @foreach($subjectActions as $subjectAction)
                                        <li>
                                            {{ __('Volunteer subject: :subject', ['subject' => $subjectAction->character->listName]) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
