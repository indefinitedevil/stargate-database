@php
    use App\Models\ActionType;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Submit Downtime') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Submit Downtime') }}
        </h2>
    </x-slot>

    <form action="{{ route('downtimes.store-submission') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if ($downtime->isOpen())
                    <div class="float-right">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>
                @endif
                <input type="hidden" name="character_id" value="{{ $character->id }}">
                <input type="hidden" name="downtime_id" value="{{ $downtime->id }}">
                <div class="space-y-2">
                    <p>
                        <strong>{{ __('Name') }}:</strong> {{ $character->name }}
                    </p>
                    <p>
                        <strong>{{ __('Downtime') }}:</strong> {{ $downtime->name }}
                        ({{ $downtime->start_time->format('d/m/Y H:i') }}
                        - {{ $downtime->end_time->format('d/m/Y H:i') }})
                        - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
                    </p>
                </div>
            </div>
        </div>

        @include('characters.partials.reset')

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <p>{{ __('You can come back and edit your downtime submission at any point until downtime closes.') }}</p>
                <p>{{ __('Information on training courses being run will be made available as teachers submit their actions.') }}</p>
            </div>
        </div>

        @include('downtimes.partials.training-courses')

        @if ($downtime->isOpen() && $character->upkeepSkills->count())
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-5 shadow">
                <p class="font-bold">{{ __('Upkeep Skills') }}</p>
                <p>{{ __('The following skills require development or research actions to be spent on upkeep:') }}</p>
                <ul class="list-disc list-inside">
                    @foreach($character->upkeepSkills as $skill)
                        <li>{{ $skill->name }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="sm:grid sm:grid-cols-2 sm:gap-6">
            @if ($downtime->development_actions > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-{{ $downtime->development_actions }}">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        <p>{{ __('In order to train a skill you first need to add the skill via the skills form below or the skills page for your character.') }}</p>
                        @php
                            $actionTypes = ActionType::where('type', ActionType::DEVELOPMENT)->get();
                            $disableMissions = $downtime->missions->count() == 0;
                            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                ->whereIn('action_type_id', [ActionType::TRAINING, ActionType::TEACHING, ActionType::UPKEEP, ActionType::MISSION])
                                ->get();
                            $actionCount = 0;

                            $skillsOptions = view('characters.partials.available-skills', ['character' => $character])->render();
                        @endphp
                        @foreach ($savedActions as $action)
                            <div>
                                <p class="text-lg">{{ __('Development Action :number', ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="development_action[{{ $actionCount }}][id]"
                                       value="{{ $action->id }}">
                                <x-select id="development_action_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if ($action->action_type_id == $type->id) selected @endif
                                                @if (ActionType::MISSION == $type->id && $disableMissions) disabled @endif
                                        >{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="development_skill_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block">
                                    @if (ActionType::TRAINING == $action->action_type_id)
                                        @include('downtimes.partials.training-skills', ['action' => $action])
                                    @elseif (ActionType::TEACHING == $action->action_type_id)
                                        @include('downtimes.partials.teaching-skills')
                                    @elseif (ActionType::UPKEEP == $action->action_type_id)
                                        @include('downtimes.partials.upkeep-skills')
                                    @endif
                                </x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::TRAINING }}"
                                          class="hidden">@include('downtimes.partials.training-skills', ['action' => $action])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::TEACHING }}"
                                          class="hidden">@include('downtimes.partials.teaching-skills', ['action' => $action])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::UPKEEP }}"
                                          class="hidden">@include('downtimes.partials.upkeep-skills', ['action' => $action])</x-select>

                                <x-input-label for="development_action_{{ $actionCount }}_notes" class="mt-1"
                                               :value="__('Notes')"/>
                                <x-textarea id="development_action_{{ $actionCount }}_notes"
                                            name="development_action[{{ $actionCount }}][notes]"
                                            :value="$action->notes"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Notes')"/>
                            </div>
                        @endforeach
                        @while($actionCount < $downtime->development_actions)
                            <div>
                                <p class="text-lg">{{ __('Development Action :number', ['number' => ++$actionCount]) }}</p>
                                <x-select id="development_action_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if (ActionType::MISSION == $type->id && $disableMissions) disabled @endif
                                        >{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="development_skill_{{ $actionCount }}"
                                          name="development_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block">
                                    @include('downtimes.partials.training-skills', ['action' => null])
                                </x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::TRAINING }}"
                                          class="hidden">@include('downtimes.partials.training-skills', ['action' => null])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::TEACHING }}"
                                          class="hidden">@include('downtimes.partials.teaching-skills', ['action' => null])</x-select>
                                <x-select id="ds_{{ $actionCount }}_{{ ActionType::UPKEEP }}"
                                          class="hidden">@include('downtimes.partials.upkeep-skills', ['action' => null])</x-select>

                                <x-input-label for="development_action_{{ $actionCount }}_notes" class="mt-1"
                                               :value="__('Notes')"/>
                                <x-textarea id="development_action_{{ $actionCount }}_notes"
                                            name="development_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Notes')"/>
                            </div>
                        @endwhile
                    </div>
                </div>
            @endif

            @if ($downtime->research_actions > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-{{ $downtime->research_actions }}">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        @php
                            $actionTypes = ActionType::where('type', ActionType::RESEARCH)->get();
                            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                ->whereIn('action_type_id', [ActionType::RESEARCHING, ActionType::UPKEEP_2])
                                ->get();
                            $actionCount = 0;
                        @endphp
                        @foreach ($savedActions as $action)
                            <div>
                                <p class="text-lg">{{ __('Research Action :number', ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="research_action[{{ $actionCount }}][id]"
                                       value="{{ $action->id }}">
                                <x-select id="research_action_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    <option value="0">{{ __('No action') }}</option>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}"
                                                @if ($action->type == $type->id) selected @endif
                                        >{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="upkeep_skill_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">
                                    @include('downtimes.partials.upkeep-skills', ['action' => $action])
                                </x-select>
                                <x-select id="research_project_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][research_project_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">@include('downtimes.partials.research', ['action' => $action])</x-select>

                                <x-input-label for="research_action_{{ $actionCount }}_notes" class="mt-1"
                                               :value="__('Notes')"/>
                                <x-textarea id="research_action_{{ $actionCount }}_notes"
                                            name="research_action[{{ $actionCount }}][notes]"
                                            :value="$action->notes"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Notes')"/>
                            </div>
                        @endforeach
                        @while($actionCount < $downtime->research_actions)
                            <div>
                                <p class="text-lg">{{ __('Research Action :number', ['number' => ++$actionCount]) }}</p>
                                <x-select id="research_action_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][type]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block" required>
                                    <option value="0">{{ __('No action') }}</option>
                                    @foreach($actionTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-select id="upkeep_skill_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][skill_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">
                                    @include('downtimes.partials.upkeep-skills', ['action' => null])
                                </x-select>
                                <x-select id="research_project_{{ $actionCount }}"
                                          name="research_action[{{ $actionCount }}][research_project_id]"
                                          :disabled="!$downtime->isOpen()"
                                          class="mt-1 block hidden">@include('downtimes.partials.research', ['action' => null])</x-select>

                                <x-input-label for="research_action_{{ $actionCount }}_notes" class="mt-1"
                                               :value="__('Notes')"/>
                                <x-textarea id="research_action_{{ $actionCount }}_notes"
                                            name="research_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Notes')"/>
                            </div>
                        @endwhile
                    </div>
                </div>
            @endif

            @if ($downtime->other_actions > 0)
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-{{ $downtime->other_actions }}">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        @php
                            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                                ->whereIn('action_type_id', [ActionType::MISC])
                                ->get();
                            $actionCount = 0;
                        @endphp
                        @foreach ($savedActions as $action)
                            <div>
                                <p class="text-lg">{{ trans_choice('Miscellaneous Actions|Miscellaneous Action :number', $downtime->other_actions, ['number' => ++$actionCount]) }}</p>
                                <input type="hidden" name="other_action[{{ $actionCount }}][id]"
                                       value="{{ $action->id }}">
                                <input type="hidden" name="other_action[{{ $actionCount }}][type]"
                                       value="{{ ActionType::OTHER }}">
                                <x-textarea id="other_action_{{ $actionCount }}_notes"
                                            name="other_action[{{ $actionCount }}][notes]"
                                            :value="$action->notes"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Any other actions or information you want to pass to the plot coordinator.')"/>
                            </div>
                        @endforeach
                        @while($actionCount < $downtime->other_actions)
                            <div>

                                <p class="text-lg">{{ trans_choice('Miscellaneous Actions|Miscellaneous Action :number', $downtime->other_actions, ['number' => ++$actionCount]) }}</p>
                                <x-textarea id="other_action_{{ $actionCount }}_notes"
                                            name="other_action[{{ $actionCount }}][notes]"
                                            :disabled="!$downtime->isOpen()"
                                            class="mt-1 block w-full"
                                            :placeholder="__('Any other actions or information you want to pass to the plot coordinator.')"/>
                            </div>
                        @endwhile
                    </div>
                </div>
            @endif


            @if ($downtime->isOpen())
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-1 sm:col-span-2">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                        <p>{{ __('You can come back and edit your downtime submission at any point until downtime closes.') }}</p>
                        <x-primary-button>{{ __('Save your progress') }}</x-primary-button>
                    </div>
                </div>
            @endif
        </div>
    </form>
    <script>
        window.onload = function () {
            jQuery('[id^="development_action_"]').on('change', function () {
                let id = jQuery(this).attr('id').split('_').pop();
                jQuery('[id^="development_skill_' + id).html(jQuery('#ds_' + id + '_' + jQuery(this).val()).html());
            });
            jQuery('[id^="research_action_"]').on('change', function () {
                let id = jQuery(this).attr('id').split('_').pop();
                if (jQuery(this).val() == {{ ActionType::UPKEEP_2 }}) {
                    jQuery('#upkeep_skill_' + id).removeClass('hidden');
                    jQuery('#research_project_' + id).addClass('hidden');
                }
                if (jQuery(this).val() == {{ ActionType::RESEARCHING }}) {
                    jQuery('#upkeep_skill_' + id).addClass('hidden');
                    jQuery('#research_project_' + id).removeClass('hidden');
                }
            });
        }
    </script>

    @include('characters.partials.add-skill')
</x-app-layout>
