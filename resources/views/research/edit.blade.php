@php
    use App\Models\ResearchProject;
    use App\Models\Skill;
    $title = empty($project->id) ? __('Create research project') : sprintf(__('Edit research project: %s'), $project->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="mt-1">
            <form method="POST" action="{{ route('research.store') }}">
                @csrf
                @if (!empty($project->id))
                    <input type="hidden" name="id" value="{{ $project->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-3">
                        <x-input-label for="name" :value="__('Project Name')"/>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $project->name ?? '')" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div class="col-span-3">
                        <x-input-label for="research_subject" :value="__('Subject of Research')"/>
                        <x-text-input id="research_subject" name="research_subject" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('research_subject', $project->research_subject ?? '')" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('research_subject')"/>
                    </div>

                    <div class="col-span-3">
                        <x-input-label for="project_goals" :value="__('Project Goals')"/>
                        <x-textarea id="project_goals" name="project_goals" rows="6"
                                    class="mt-1 block w-full">{{ $project->project_goals ?? '' }}</x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('project_goals')"/>
                        <p class="text-xs">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                    </div>

                    <div class="col-span-3">
                        <x-input-label for="ooc_intent" :value="__('OOC Intent')"/>
                        <x-textarea id="ooc_intent" name="ooc_intent" rows="6"
                                    class="mt-1 block w-full">{{ $project->ooc_intent ?? '' }}</x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('ooc_intent')"/>
                        <p class="text-xs">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                    </div>

                    @can('approve research projects')
                        <div class="col-span-3">
                            <x-input-label for="plot_notes" :value="__('Plot Notes')"/>
                            <x-textarea id="plot_notes" name="plot_notes" rows="6"
                                        class="mt-1 block w-full">{{ $project->ooc_intent ?? '' }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('plot_notes')"/>
                            <p class="text-xs">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        </div>

                        <div class="col-span-3">
                            <x-input-label for="results" :value="__('Results')"/>
                            <x-textarea id="results" name="results" rows="6"
                                        class="mt-1 block w-full">{{ $project->ooc_intent ?? '' }}</x-textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('results')"/>
                            <p class="text-xs">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        </div>
                    @endcan

                    <div>
                        <x-input-label for="status" :value="__('Status')"/>
                        @if (empty($project->id))
                            <x-text-input id="status" name="status" type="hidden" required
                                          value="{{ ResearchProject::STATUS_PENDING }}"/>
                            <x-text-input type="text" class="mt-1 block w-full" :value="__('Pending')" disabled/>
                        @else
                            <x-select id="status" name="status" class="mt-1 block w-full" required>
                                @can('approve research projects')
                                    @php
                                        $statuses = [ResearchProject::STATUS_PENDING, ResearchProject::STATUS_APPROVED];
                                    @endphp
                                @elsecan('edit research projects')
                                    @php
                                        $statuses = [ResearchProject::STATUS_APPROVED, ResearchProject::STATUS_ON_HOLD, ResearchProject::STATUS_COMPLETED, ResearchProject::STATUS_ABANDONED];
                                    @endphp
                                @endcan
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}"
                                            @if(old('status', $project->status ?? '') == $status) selected @endif>{{ ResearchProject::getStatusName($status) }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')"/>
                        @endif
                    </div>

                    <div>
                        <x-input-label for="visibility" :value="__('Visibility')"/>
                        <x-select id="visibility" name="visibility" class="mt-1 block w-full" required>
                            @foreach ([ResearchProject::VISIBILITY_PRIVATE, ResearchProject::VISIBILITY_PUBLIC, ResearchProject::VISIBILITY_ARCHIVED] as $visibility)
                                <option value="{{ $visibility }}"
                                        @if(old('visibility', $project->visibility ?? '') == $visibility) selected @endif>{{ ResearchProject::getVisibilityName($visibility) }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error class="mt-2" :messages="$errors->get('visibility')"/>
                    </div>

                    @can('approve research projects')
                        <div>
                            <x-input-label for="months" :value="__('Months required')"/>
                            <x-text-input id="months" name="months" type="number"
                                          class="mt-1 block w-full"
                                          :value="old('months', $project->months ?? 3)"
                                          required/>
                            <x-input-error class="mt-2" :messages="$errors->get('months')"/>
                        </div>
                    @endcan

                    <div class="sm:col-span-2">
                        <x-input-label for="parent_project_id" :value="__('Parent Project')"/>
                        @if ($parentProjects->count() == 0)
                            <x-text-input type="text" class="mt-1 block w-full" disabled
                                          value="{{ __('No completed projects') }}"/>
                        @else
                            <x-select id="parent_project_id" name="parent_project_id" class="mt-1 block w-full">
                                <option value="">{{ __('No parent project') }}</option>
                                @foreach ($parentProjects as $project)
                                    <option value="{{ $project->id }}"
                                            @if(old('parent_project_id', $project->parent_project_id ?? '') == $project->id) selected @endif>{{ $project->name }}</option>
                                @endforeach
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('parent_project_id')"/>
                        @endif
                    </div>

                    @can('approve research projects')
                        <div>
                            <x-input-label for="needs_volunteers" :value="__('Needs volunteers')"/>
                            <x-checkbox-input id="needs_volunteers" name="needs_volunteers" type="number"
                                              :value="old('needs_volunteers', $project->needs_volunteers ?? false)"/>
                            <x-input-error class="mt-2" :messages="$errors->get('needs_volunteers')"/>
                        </div>

                        <div class="col-span-2">
                            <x-input-label for="skills" :value="__('Skills required')"/>
                            <x-select id="skills" name="skills[]" class="mt-1 block w-full" multiple>
                                @php $skillIds = $project->skills->pluck('id')->toArray() ?? []; @endphp
                                @foreach (Skill::where('skill_category_id', '!=', 7)->get() as $skill)
                                    @if (empty($currentCategory) || $currentCategory != $skill->skill_category_id)
                                        @if (!empty($currentCategory))
                                            {!! '</optgroup>' !!}
                                        @endif
                                        @php $currentCategory = $skill->skill_category_id; @endphp
                                        {!! '<optgroup label="' . __(':name Skills', ['name' => $skill->skillCategory->name]) . '">' !!}
                                    @endif
                                    <option value="{{ $skill->id }}"
                                            @if (in_array($skill->id, old('skills', $skillIds))) selected @endif>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                                @if (!empty($currentCategory))
                                    {!! '</optgroup>' !!}
                                @endif
                            </x-select>
                            <x-input-error class="mt-2" :messages="$errors->get('skills')"/>
                            @endcan
                        </div>
                </div>
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
