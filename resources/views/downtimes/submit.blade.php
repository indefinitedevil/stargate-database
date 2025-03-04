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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form action="{{ route('downtimes.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                        <input type="hidden" name="character_id" value="{{ $character->id }}">
                        <input type="hidden" name="downtime_id" value="{{ $downtime->id }}">
                        <div>
                            <x-input-label for="name" :value="__('Name')"/>
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                          :value="$character->name" disabled/>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                        @if ($downtime->development_actions > 0)
                            @php
                                $actionTypes = ActionType::where('type', ActionType::DEVELOPMENT)->get();
                                $disableMissions = $downtime->missions->count() == 0;
                                $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)->get();
                                $actionCount = 0;

                                $skillsOptions = view('characters.partials.available-skills', ['character' => $character])->render();
                            @endphp
                            @foreach ($savedActions as $action)
                                <div>
                                    <p class="text-lg">{{ __('Development Action :number', ['number' => ++$actionCount]) }}</p>
                                    <x-input-label for="development_action_{{ $action->id }}"
                                                   :value="__('Action Type')"/>
                                    <x-select id="development_action_{{ $action->id }}"
                                              name="development_action[{{ $action->id }}][type]"
                                              class="mt-1 block" required>
                                        @foreach($actionTypes as $type)
                                            <option value="{{ $type->id }}"
                                                    @if ($action->type == $type->id) selected @endif
                                                    @if (ActionType::MISSION == $type->id && $disableMissions) disabled @endif
                                            >{{ $type->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-input-label for="development_skill_{{ $action->id }}" :value="__('Skill')"/>
                                    <x-select id="development_skill_{{ $action->id }}"
                                              name="development_action[{{ $action->id }}][skill_id]"
                                              class="mt-1 block">
                                        @if (ActionType::TRAINING == $action->action_type_id)
                                            @include('downtimes.partials.training-skills', ['action' => $action])
                                        @elseif (ActionType::TEACHING == $action->action_type_id)
                                            @include('downtimes.partials.teaching-skills')
                                        @elseif (ActionType::UPKEEP == $action->action_type_id)
                                            @include('downtimes.partials.upkeep-skills')
                                        @endif
                                    </x-select>
                                    <x-select id="ds_{{ $action->id }}_{{ ActionType::TRAINING }}" class="hidden">@include('downtimes.partials.training-skills', ['action' => $action])</x-select>
                                    <x-select id="ds_{{ $action->id }}_{{ ActionType::TEACHING }}" class="hidden">@include('downtimes.partials.teaching-skills', ['action' => $action])</x-select>
                                    <x-select id="ds_{{ $action->id }}_{{ ActionType::UPKEEP }}" class="hidden">@include('downtimes.partials.upkeep-skills', ['action' => $action])</x-select>
                                </div>
                            @endforeach
                            @while($actionCount < $downtime->development_actions)
                                <div>
                                    <p class="text-lg">{{ __('Development Action :number', ['number' => ++$actionCount]) }}</p>
                                    <x-input-label for="development_action_{{ $actionCount }}"
                                                   :value="__('Action Type')"/>
                                    <x-select id="development_action_{{ $actionCount }}"
                                              name="development_action[][type]"
                                              class="mt-1 block" required>
                                        @foreach($actionTypes as $type)
                                            <option value="{{ $type->id }}"
                                                    @if (ActionType::MISSION == $type->id && $disableMissions) disabled @endif
                                            >{{ $type->name }}</option>
                                        @endforeach
                                    </x-select>
                                    <x-select id="development_skill_{{ $actionCount }}"
                                              name="development_action[{{ $actionCount }}][skill_id]"
                                              class="mt-1 block">
                                        @include('downtimes.partials.training-skills', ['action' => null])
                                    </x-select>
                                    <x-select id="ds_{{ $actionCount }}_{{ ActionType::TRAINING }}" class="hidden">@include('downtimes.partials.training-skills', ['action' => null])</x-select>
                                    <x-select id="ds_{{ $actionCount }}_{{ ActionType::TEACHING }}" class="hidden">@include('downtimes.partials.teaching-skills', ['action' => null])</x-select>
                                    <x-select id="ds_{{ $actionCount }}_{{ ActionType::UPKEEP }}" class="hidden">@include('downtimes.partials.upkeep-skills', ['action' => null])</x-select>
                                </div>
                            @endwhile
                            <script>
                                window.onload = function() {
                                    jQuery('[id^="development_action_"]').on('change', function () {
                                        let id = jQuery(this).attr('id').split('_').pop();
                                        jQuery('[id^="development_skill_' + id).html(jQuery('#ds_' + id + '_' + jQuery(this).val()).html());
                                    });
                                }
                            </script>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
