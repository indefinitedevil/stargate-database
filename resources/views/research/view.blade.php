@php
    use App\Models\ResearchProject;
    /** @var ResearchProject $project */
@endphp
<x-app-layout>
    <x-slot name="title">{{ sprintf(__('Research project: %s'), $project->name) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <div class="sm:float-right sm:grid sm:grid-cols-2 gap-2">
                @can ('edit research projects')
                    <a href="{{ route('research.edit', ['projectId' => $project]) }}"
                       class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                    >{{ __('Edit') }}</a>
                @endcan
                @can ('delete research projects')
                    @if (ResearchProject::STATUS_PENDING == $project->status)
                        <a href="{{ route('research.delete', ['projectId' => $project]) }}"
                           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                           onclick="return confirm('{{ __('Are you sure you want to delete this research project?') }}')"
                        >{{ __('Delete') }}</a>
                    @endif
                @endcan
            </div>
            {{ sprintf(__('Research project: %s'), $project->name) }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 sm:grid sm:grid-cols-4 gap-x-6 gap-y-2">
            <p class="col-span-2">
                <strong>{{ __('Project name') }}:</strong> {{ $project->name }}
            </p>
            <p class="col-span-2">
                <strong>{{ __('Subject of research') }}:</strong> {{ $project->research_subject }}
            </p>
            <p>
                <strong>{{ __('Status') }}:</strong> {{ $project->status_name }}
            </p>
            <p>
                <strong>{{ __('Visibility') }}:</strong> {{ $project->visibility_name }}
            </p>
            <p>
                <strong>{{ __('Project length') }}:</strong> {{ __(':months months', ['months' => $project->months]) }}
            </p>
            <p>
                <strong>{{ __('Needs subjects') }}:</strong> {{ $project->needs_volunteers ? __('Yes') : __('No') }}
            </p>
        </div>
    </div>

    <div class="sm:grid sm:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <h3 class="text-xl">{{ __('Project Goals') }}</h3>
                {!! process_markdown($project->project_goals) !!}
            </div>
        </div>
        @can('edit research projects')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-xl">{{ __('OOC Intent') }}</h3>
                    {!! process_markdown($project->ooc_intent) !!}
                    <em class="text-xs">{{ __('This field is intended to be used to explain in OOC terms what you\'re trying to achieve, and what you expect to get.') }}</em>
                </div>
            </div>
        @endcan
        @if (ResearchProject::STATUS_COMPLETED == $project->status)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-xl">{{ __('Results') }}</h3>
                    {!! process_markdown($project->results) !!}
                </div>
            </div>
        @endif
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <h3 class="text-xl">{{ __('Skills required') }}</h3>
                <ul class="list-disc list-inside">
                    @foreach($project->skills as $skill)
                        <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @if (ResearchProject::STATUS_ACTIVE <= $project->status && $project->downtimeActions->count())
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-xl">{{ __('Researchers') }}</h3>
                    @if ($project->researchActions->count())
                        <ul class="list-disc list-inside">
                            @foreach($project->researchActions as $downtimeAction)
                                <li>
                                    {{ $downtimeAction->character->listName }}
                                    @if($downtimeAction->downtime->isOpen())
                                        <em>{{ __('(Pending)') }}</em>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ __('No researchers have been registered for this project yet.') }}</p>
                    @endif
                    @if ($project->needs_volunteers)
                        <h3 class="text-xl">{{ __('Volunteer Subjects') }}</h3>
                        @if ($project->subjectActions->count())
                            <ul class="list-disc list-inside">
                                @foreach($project->subjectActions as $subjectAction)
                                    <li>
                                        {{ $subjectAction->character->listName }}
                                        @if($subjectAction->downtime->isOpen())
                                            <em>{{ __('(Pending)') }}</em>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>{{ __('No volunteer subjects have been registered for this project yet.') }}</p>
                        @endif
                    @endif
                </div>
            </div>
        @endif
        @can('view hidden notes')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-xl">{{ __('Plot Notes') }}</h3>
                    {!! process_markdown($project->plot_notes) !!}
                </div>
            </div>
        @endcan
    </div>
</x-app-layout>
