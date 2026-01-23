@php
    use App\Models\ActionType;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Downtime Actions for :downtime', ['downtime' => $downtime->name]) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Downtime Actions for :downtime', ['downtime' => $downtime->name]) }}
        </h2>
    </x-slot>

    @include('partials.errors')
    <form action="{{ route('downtimes.store-submission') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <input type="hidden" name="character_id" value="{{ $character->id }}">
                <input type="hidden" name="downtime_id" value="{{ $downtime->id }}">
                <div class="space-y-2">
                    <p>
                        <strong>{{ __('Name') }}:</strong> {{ $character->name }}
                    </p>
                    <p>
                        <strong>{{ __('Downtime') }}:</strong> {{ $downtime->name }}
                        @if ($downtime->event_id)
                            ({{ $downtime->event->name }})
                        @endif
                        ({{ format_datetime($downtime->start_time, 'd/m/Y H:i') }}
                        - {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }})
                        - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                    </p>
                </div>
            </div>
        </div>

        @include('characters.partials.reset')

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <p>
                    {{ __('You do not need to fill in all details immediately.') }}
                    {{ __('You can come back and edit your downtime actions at any point until downtime closes.') }}
                </p>
                <p>{{ __('You don\'t need to finalise your submission - saved actions will all be taken into account when downtime closes.') }}</p>
                <p>{{ __('Information on training courses being run will be made available as teachers submit their actions.') }}</p>
            </div>
        </div>

        <div class="sm:grid sm:grid-cols-2 sm:gap-6">
            @include('downtimes.partials.training-courses')

            @include('characters.partials.missing-specialties')

            @if ($downtime->isOpen() && $character->requiredUpkeepSkills->count())
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-5 shadow">
                    <p class="font-bold">{{ __('Upkeep Skills') }}</p>
                    <p>{{ __('The following skills require actions to be spent on upkeep or a level of the skill will be lost:') }}</p>
                    <ul class="list-disc list-inside">
                        @foreach($character->requiredUpkeepSkills as $skill)
                            <li>{{ $skill->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($downtime->development_actions > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-{{ $downtime->development_actions }}">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        <p>{!! __('In order to train a skill you first need to add the skill via <a href="#add-skill" class="underline">the skills form below</a> or the skills page for your character.') !!}</p>
                        <p>{{ __('If you are training a skill that has pre-requisites, all pre-reqs must be completed before the downtime in which you begin training.') }}</p>
                        <p>{{ __('If you are training a skill that has levels or that can be repeatedly trained, all previous levels must be completed before the downtime in which you begin training a new level.') }}</p>
                        @php
                            $actionTypes = ActionType::where('type', ActionType::TYPE_DEVELOPMENT)->get();
                            $disableMissions = $downtime->missions->count() == 0;
                            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                ->whereIn('action_type_id', [ActionType::ACTION_TRAINING, ActionType::ACTION_TEACHING, ActionType::ACTION_UPKEEP, ActionType::ACTION_MISSION])
                                ->get();
                            $actionCount = 0;

                            $skillsOptions = view('characters.partials.available-skills', ['character' => $character])->render();
                        @endphp
                        @foreach ($savedActions as $action)
                            <div>
                                <p class="text-lg">{{ trans_choice('Development Action|Development Action :number', $downtime->development_actions, ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="development_action[{{ $actionCount }}][id]"
                                       value="{{ $action->id }}">
                                <x-select id="development_action_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if ($action->action_type_id == $type->id) selected @endif
                                                @if (ActionType::ACTION_MISSION == $type->id && $disableMissions) disabled @endif
                                        >{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="development_skill_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block">
                                    @if (ActionType::ACTION_TRAINING == $action->action_type_id)
                                        @include('downtimes.partials.training-skills', ['action' => $action])
                                    @elseif (ActionType::ACTION_TEACHING == $action->action_type_id)
                                        @include('downtimes.partials.teaching-skills')
                                    @elseif (ActionType::ACTION_UPKEEP == $action->action_type_id)
                                        @include('downtimes.partials.upkeep-skills')
                                    @endif
                                </x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::ACTION_TRAINING }}"
                                          class="hidden">@include('downtimes.partials.training-skills', ['action' => $action])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::ACTION_TEACHING }}"
                                          class="hidden">@include('downtimes.partials.teaching-skills', ['action' => $action])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::ACTION_UPKEEP }}"
                                          class="hidden">@include('downtimes.partials.upkeep-skills', ['action' => $action])</x-select>
                                <x-input-error class="mt-2" :messages="$errors->get('development_action_'.$actionCount)" />

                                <p id="da_{{ $actionCount }}_teaching"
                                   class="{{ ActionType::ACTION_TEACHING == $action->action_type_id ? '' : 'hidden' }} text-sm mt-1">{{ __('Teaching a training course provides you with +1 maximum Vigor for the next event.') }}</p>
                                <div id="da_{{ $actionCount }}_notes"
                                     class="{{ ActionType::ACTION_MISSION == $action->action_type_id ? '' : 'hidden' }}">
                                    <x-input-label for="development_action_{{ $actionCount }}_notes" class="mt-1"
                                                   :value="__('Notes')"/>
                                    <x-textarea id="development_action_{{ $actionCount }}_notes"
                                                name="development_action[{{ $actionCount }}][notes]"
                                                :value="old('development_action[' . $actionCount . '][notes]', $action->notes)"
                                                :disabled="!$downtime->isOpen()"
                                                class="mt-1 block w-full"
                                                :placeholder="__('Relevant notes regarding your action')">{{ old('development_action[' . $actionCount . '][notes]', $action->notes) }}</x-textarea>
                                </div>
                            </div>
                        @endforeach
                        @while($actionCount < $downtime->development_actions)
                            <div>
                                <p class="text-lg">{{ trans_choice('Development Action|Development Action :number', $downtime->development_actions, ['number' => ++$actionCount]) }}</p>
                                <x-select id="development_action_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if (ActionType::ACTION_MISSION == $type->id && $disableMissions) disabled @endif
                                        >{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="development_skill_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block">
                                    @include('downtimes.partials.training-skills', ['action' => null])
                                </x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::ACTION_TRAINING }}"
                                          class="hidden">@include('downtimes.partials.training-skills', ['action' => null])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::ACTION_TEACHING }}"
                                          class="hidden">@include('downtimes.partials.teaching-skills', ['action' => null])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::ACTION_UPKEEP }}"
                                          class="hidden">@include('downtimes.partials.upkeep-skills', ['action' => null])</x-select>
                                <x-input-error class="mt-2" :messages="$errors->get('development_action_'.$actionCount)" />

                                <p id="da_{{ $actionCount }}_teaching"
                                   class="hidden text-sm mt-1">{{ __('Teaching a training course provides you with +1 maximum Vigor for the next event.') }}</p>
                                <div id="da_{{ $actionCount }}_notes" class="hidden">
                                    <x-input-label for="development_action_{{ $actionCount }}_notes" class="mt-1"
                                                   :value="__('Notes')"/>
                                    <x-textarea id="development_action_{{ $actionCount }}_notes"
                                                name="development_action[{{ $actionCount }}][notes]"
                                                :disabled="!$downtime->isOpen()"
                                                class="mt-1 block w-full"
                                                :placeholder="__('Relevant notes regarding your action')"/>
                                </div>
                            </div>
                        @endwhile
                    </div>
                </div>
            @endif

            @include('downtimes.partials.research-info')

            @if ($downtime->research_actions > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-{{ $downtime->research_actions + $downtime->experiment_actions }}">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        @php
                            $actionTypes = ActionType::where('type', ActionType::TYPE_RESEARCH)->get();
                            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                ->whereIn('action_type_id', [ActionType::ACTION_RESEARCHING, ActionType::ACTION_UPKEEP_2])
                                ->get();
                            $actionCount = 0;
                        @endphp
                        @foreach ($savedActions as $action)
                            <div>
                                <p class="text-lg">{{ trans_choice('Research Action|Research Action :number', $downtime->research_actions, ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="research_action[{{ $actionCount }}][id]"
                                       value="{{ $action->id }}">
                                <x-select id="research_action_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    <option value="0">{{ __('No action') }}</option>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if ($action->action_type_id == $type->id) selected @endif
                                        >{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="research_project_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][research_project_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block {{ ActionType::ACTION_RESEARCHING == $action->action_type_id ? '' : 'hidden' }}">@include('downtimes.partials.research-projects', ['action' => $action])</x-select>
                                <x-select id="research_skill_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block">
                                    @if (ActionType::ACTION_UPKEEP_2 == $action->action_type_id)
                                        @include('downtimes.partials.upkeep-skills', ['action' => $action])
                                    @elseif (ActionType::ACTION_RESEARCHING == $action->action_type_id)
                                        @include('downtimes.partials.research-skills', ['action' => $action])
                                    @endif
                                </x-select>
                                <x-select id="rs_{{ $actionCount }}_{{ ActionType::ACTION_UPKEEP_2 }}"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">
                                    @include('downtimes.partials.upkeep-skills', ['action' => $action])
                                </x-select>
                                <x-select id="rs_{{ $actionCount }}_{{ ActionType::ACTION_RESEARCHING }}"
                                          class="mt-1 block hidden">@include('downtimes.partials.research-skills', ['action' => $action])</x-select>
                                <x-textarea id="research_action_{{ $actionCount }}_notes"
                                            name="research_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full {{ ActionType::ACTION_RESEARCHING == $action->action_type_id ? '' : 'hidden' }}"
                                            maxlength="2000"
                                            rows="6"
                                            :placeholder="__('Relevant notes regarding your research')">{{ old('research_action[' . $actionCount . '][notes]', $action->notes) }}</x-textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('research_action_'.$actionCount)" />
                            </div>
                        @endforeach
                        @while($actionCount < $downtime->research_actions)
                            <div>
                                <p class="text-lg">{{ trans_choice('Research Action|Research Action :number', $downtime->research_actions, ['number' => ++$actionCount]) }}</p>
                                <x-select id="research_action_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    <option value="0">{{ __('No action') }}</option>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="research_project_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][research_project_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">@include('downtimes.partials.research-projects', ['action' => null])</x-select>
                                <x-select id="research_skill_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">
                                    <option>{{ __('Select an action type') }}</option>
                                </x-select>
                                <x-select id="rs_{{ $actionCount }}_{{ ActionType::ACTION_UPKEEP_2 }}"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">
                                    @include('downtimes.partials.upkeep-skills', ['action' => null])
                                </x-select>
                                <x-select id="rs_{{ $actionCount }}_{{ ActionType::ACTION_RESEARCHING }}"
                                          class="mt-1 block hidden">@include('downtimes.partials.research-skills', ['action' => null])</x-select>
                                <x-textarea id="research_action_{{ $actionCount }}_notes"
                                            name="research_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full hidden"
                                            maxlength="2000"
                                            rows="6"
                                            :placeholder="__('Relevant notes regarding your research')"/>
                                <x-input-error class="mt-2" :messages="$errors->get('research_action_'.$actionCount)" />
                            </div>
                        @endwhile

                        @if ($downtime->experiment_actions)
                            @php
                                $actionTypes = ActionType::where('type', ActionType::TYPE_EXPERIMENT)->get();
                                $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                    ->where('action_type_id', ActionType::ACTION_RESEARCH_SUBJECT)
                                    ->get();
                                $actionCount = 0;
                            @endphp
                            @foreach ($savedActions as $action)
                                <div>
                                    <p class="text-lg">{{ trans_choice('Research Subject Action|Research Subject Action :number', $downtime->experiment_actions, ['number' => ++$actionCount]) }}</p>
                                    <input type="hidden" name="research_subject_action[{{ $actionCount }}][id]"
                                           value="{{ $action->id }}">
                                    <input type="hidden" name="research_subject_action[{{ $actionCount }}][type]"
                                           value="{{ ActionType::ACTION_RESEARCH_SUBJECT }}">
                                    <x-select id="research_subject_project_{{ $actionCount }}"
                                              name="research_subject_action[{{ $actionCount }}][research_project_id]"
                                              :disabled="!$downtime->isOpen()"
                                              class="mt-1 block">@include('downtimes.partials.research-volunteers', ['action' => $action])</x-select>
                                    <x-input-error class="mt-2" :messages="$errors->get('research_subject_action_'.$actionCount)" />
                                    <p class="text-xs mt-1">{{ __('You may consent to being the subject of a research project that requires volunteer subjects. This will not prevent you from taking other actions.') }}</p>
                                </div>
                            @endforeach
                            @if ($downtime->researchVolunteerProjects->count() > 0)
                                @while($actionCount < $downtime->experiment_actions)
                                    <div>
                                        <p class="text-lg">{{ trans_choice('Research Subject Action|Research Subject Action :number', $downtime->experiment_actions, ['number' => ++$actionCount]) }}</p>
                                        <input type="hidden" name="research_subject_action[{{ $actionCount }}][type]"
                                               value="{{ ActionType::ACTION_RESEARCH_SUBJECT }}">
                                        <x-select id="research_subject_project_{{ $actionCount }}"
                                                  name="research_subject_action[{{ $actionCount }}][research_project_id]"
                                                  :disabled="!$downtime->isOpen()"
                                                  class="mt-1 block">@include('downtimes.partials.research-volunteers', ['action' => null])</x-select>
                                        <x-input-error class="mt-2" :messages="$errors->get('research_subject_action_'.$actionCount)" />
                                        <p class="text-xs mt-1">{{ __('You may consent to being the subject of a research project that requires volunteer subjects. This will not prevent you from taking other actions.') }}</p>
                                    </div>
                                @endwhile
                            @endif
                        @endif
                    </div>
                </div>
            @endif

            @if ($downtime->other_actions > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-{{ $downtime->other_actions }}">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        @php
                            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                ->whereIn('action_type_id', [ActionType::ACTION_OTHER])
                                ->get();
                            $actionCount = 0;
                        @endphp
                        @foreach ($savedActions as $action)
                            <div>
                                <p class="text-lg">{{ trans_choice('Personal Action|Personal Action :number', $downtime->other_actions, ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="other_action[{{ $actionCount }}][id]"
                                       value="{{ $action->id }}">
                                <input type="hidden" name="other_action[{{ $actionCount }}][type]"
                                       value="{{ ActionType::ACTION_OTHER }}">
                                <x-textarea id="other_action_{{ $actionCount }}_notes"
                                            name="other_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            maxlength="2000"
                                            rows="6"
                                            :placeholder="__('Details regarding a single personal action you want to inform the plot coordinator about.')">{{ old('other_action[' . $actionCount . '][notes]', $action->notes) }}</x-textarea>
                                @if (auth()->user()->can('view hidden notes') || $downtime->processed)
                                    <x-input-label for="other_action_{{ $actionCount }}_response"
                                                   class="mt-1" :value="__('Response')"/>
                                    <x-textarea id="other_action_{{ $actionCount }}_response"
                                                name="other_action[{{ $actionCount }}][response]"
                                                :disabled="$downtime->processed"
                                                class="mt-1 block w-full"
                                                maxlength="2000"
                                                rows="6"
                                                :placeholder="__('Plot co response to above.')">{{ $action->response }}</x-textarea>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('personal_action_'.$actionCount)" />
                            </div>
                        @endforeach
                        @while($actionCount < $downtime->other_actions)
                            <div>
                                <p class="text-lg">{{ trans_choice('Personal Action|Personal Action :number', $downtime->other_actions, ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="other_action[{{ $actionCount }}][type]"
                                       value="{{ ActionType::ACTION_OTHER }}"/>
                                <x-textarea id="other_action_{{ $actionCount }}_notes"
                                            name="other_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            maxlength="2000"
                                            rows="6"
                                            :placeholder="__('Details regarding a single personal action you want to inform the plot coordinator about.')"/>
                                @if (auth()->user()->can('view hidden notes'))
                                    <x-input-label for="other_action_{{ $actionCount }}_response"
                                                   class="mt-1" :value="__('Response')"/>
                                    <x-textarea id="other_action_{{ $actionCount }}_response"
                                                name="other_action[{{ $actionCount }}][response]"
                                                :disabled="$downtime->processed"
                                                class="mt-1 block w-full"
                                                maxlength="2000"
                                                rows="6"
                                                :placeholder="__('Plot co response to above.')"></x-textarea>
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get('personal_action_'.$actionCount)" />
                            </div>
                        @endwhile
                        <p class="text-sm">
                            {{ __('This is a space for personal research actions your character is undertaking.') }}
                            {{ __('This is not a replacement for research projects but is here for queries and actions that you are personally undertaking during downtime.') }}
                        </p>
                        <p class="text-sm">
                            {{ __('An example of this may be "fortifying your laptop firewalls against alien intrusion" or "going to the library to research Welsh myths".') }}
                        </p>
                    </div>
                </div>
            @endif


            @if ($downtime->isOpen() || auth()->user()->can('view hidden notes') && !$downtime->processed)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-1">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                        <p>{{ __('You can come back and edit your downtime actions at any point until downtime closes.') }}</p>
                        <p>{{ __('You do not need to submit your downtime - all saved actions will be processed.') }}</p>
                        <p>{{ __('Save your progress before adding new skills to avoid losing your inputs.') }}</p>
                        <x-primary-button>{{ __('Save your progress') }}</x-primary-button>
                    </div>
                </div>
            @endif
        </div>
    </form>

    @include('characters.partials.add-skill')
    <script src="{{ asset('js/downtimes.js?v=20250930') }}" defer></script>
</x-app-layout>
