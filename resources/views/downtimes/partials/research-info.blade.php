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
                            <strong>{{ $researchProject->name }}</strong>
                            {{ __('(:done / :total months)', ['done' => $researchProject->researchActions()->count(), 'total' => $researchProject->months]) }}
                            <p class="ml-5">{{ __('Subject: :subject', ['subject' => $researchProject->research_subject]) }}</p>
                            @php $researchActions = $researchProject->researchActions()->where('downtime_id', $downtime->id)->get(); @endphp
                            @if ($researchActions->count() > 0)
                                <ul class="list-inside list-disc ml-4">
                                    @foreach($researchActions as $researchAction)
                                        <li>
                                            {{ __('Researching: :researcher', ['researcher' => $researchAction->character->listName]) }}
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
