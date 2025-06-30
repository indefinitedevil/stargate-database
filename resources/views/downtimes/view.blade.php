@php
    use App\Models\ActionType;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('View Downtime') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @can ('edit downtimes')
                @if ($downtime->open)
                    <div class="sm:float-right sm:grid sm:grid-cols-2 gap-2">
                        <a href="{{ route('downtimes.submit', ['downtimeId' => $downtime, 'characterId' => $character]) }}"
                           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        >{{ __('Edit') }}</a>
                        <a href="{{ route('plotco.downtimes.delete-actions', ['downtimeId' => $downtime, 'characterId' => $character]) }}"
                           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                           onclick="return confirm('{{ __('Are you sure you want to delete these downtime actions?') }}')"
                        >{{ __('Delete') }}</a>
                    </div>
                @endif
            @endcan
            {{ __('View Downtime') }}
        </h2>
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
            <p>
                <strong>{{ __('Name') }}:</strong>
                @can ('view all characters')
                    <a href="{{ $character->getViewRoute() }}" class="underline">{{ $character->name }}</a>
                @else
                    {{ $character->name }}
                @endcan
            </p>
            <p>
                <strong>{{ __('Downtime') }}:</strong> {{ $downtime->name }}
                ({{ format_datetime($downtime->start_time, 'd/m/Y H:i') }}
                - {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }})
                - {{ $downtime->isOpen() ? __('Open') : __('Closed') }}
            </p>
        </div>
    </div>

    <div class="sm:grid sm:grid-cols-2 sm:gap-6">
        @php
            $savedActions = $character->downtimeActions()->where('downtime_id', $downtime->id)
                ->whereIn('action_type_id', [ActionType::ACTION_TRAINING, ActionType::ACTION_TEACHING, ActionType::ACTION_UPKEEP, ActionType::ACTION_MISSION])
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
                                @case(ActionType::ACTION_TRAINING)
                                @case(ActionType::ACTION_TEACHING)
                                @case(ActionType::ACTION_UPKEEP)
                                    <p>{!! __('<strong>Skill:</strong> :skill', ['skill' => $action->characterSkill->name]) !!}</p>
                                    @break
                                @case(ActionType::ACTION_MISSION)
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
                ->whereIn('action_type_id', [ActionType::ACTION_RESEARCHING, ActionType::ACTION_UPKEEP_2])
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
                                @case(ActionType::ACTION_UPKEEP_2)
                                    <p>{!! __('<strong>Skill:</strong> :skill', ['skill' => $action->characterSkill->name]) !!}</p>
                                    @break
                                @case(ActionType::ACTION_RESEARCHING)
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
                ->whereIn('action_type_id', [ActionType::ACTION_OTHER])
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

        @if ($downtime->response && $downtime->processed)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-xl font-semibold">{{ __('Downtime Response') }}</h3>
                    {!! process_markdown($downtime->response) !!}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
