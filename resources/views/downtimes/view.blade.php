@php
    use App\Models\ActionType;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('View Downtime') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('View Downtime') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
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

    <div class="sm:grid sm:grid-cols-2 sm:gap-6">
        @php
            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                ->whereIn('action_type_id', [ActionType::TRAINING, ActionType::TEACHING, ActionType::UPKEEP, ActionType::MISSION])
                ->get();
            $actionCount = 0;
        @endphp
        @if (count($savedActions) > 0)
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-{{ $downtime->development_actions }}">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    @foreach ($savedActions as $action)
                        <div>
                            <p class="text-lg">{{ __('Development Action :number', ['number' => ++$actionCount]) }}</p>
                            <p><strong>Type:</strong> {{ $action->actionType->name }}</p>
                            @switch($action->action_type_id)
                                @case(ActionType::TRAINING)
                                @case(ActionType::TEACHING)
                                @case(ActionType::UPKEEP)
                                    <p>{!! __('<strong>Skill:</strong> :skill', ['skill' => $action->characterSkill->name]) !!}</p>
                                    @break
                                @case(ActionType::MISSION)
                                    <p>{!! __('<strong>Mission:</strong> :mission', ['mission' => $action->mission->name]) !!}</p>
                                    @break
                            @endswitch
                            <p>{!! __('<strong>Notes:</strong> :notes', ['notes' => $action->notes]) !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @php
            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                ->whereIn('action_type_id', [ActionType::RESEARCHING, ActionType::UPKEEP_2])
                ->get();
            $actionCount = 0;
        @endphp
        @if (count($savedActions) > 0)
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-{{ $downtime->research_actions }}">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    @foreach ($savedActions as $action)
                        <div>
                            <p class="text-lg">{{ __('Research Action :number', ['number' => ++$actionCount]) }}</p>
                            <p><strong>Type:</strong> {{ $action->actionType->name }}</p>
                            @switch($action->action_type_id)
                                @case(ActionType::UPKEEP_2)
                                    <p>{!! __('<strong>Skill:</strong> :skill', ['skill' => $action->characterSkill->name]) !!}</p>
                                    @break
                                @case(ActionType::RESEARCHING)
                                    <p>{!! __('<strong>Project:</strong> :project', ['project' => $action->researchProject->name]) !!}</p>
                                    @break
                            @endswitch
                            <p>{!! __('<strong>Notes:</strong> :notes', ['notes' => $action->notes]) !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @php
            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                ->whereIn('action_type_id', [ActionType::MISC])
                ->get();
            $actionCount = 0;
        @endphp
        @if (count($savedActions) > 0)
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg row-span-{{ $downtime->other_actions }}">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    @foreach ($savedActions as $action)
                        <div>
                            <p class="text-lg">{{ __('Miscellaneous Action :number', ['number' => ++$actionCount]) }}</p>
                            <p>{!! __('<strong>Notes:</strong> :notes', ['notes' => $action->notes]) !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
