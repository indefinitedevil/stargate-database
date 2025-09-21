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
    <x-slot name="sidebar2">
        @if ($downtime->open || !$downtime->processed)
            <x-dropdown-link
                href="{{ route('downtimes.submit', ['downtimeId' => $downtime, 'characterId' => $character]) }}">
                <i class="fa-solid fa-pen min-w-8"></i>
                {{ __('Edit') }}
            </x-dropdown-link>
        @endif
        @can ('edit downtimes')
            @if ($downtime->open)
                <form method="POST"
                      action="{{ route('plotco.downtimes.delete-actions', ['downtimeId' => $downtime, 'characterId' => $character]) }}"
                      onsubmit="return confirm('{{ __('Are you sure you want to delete these downtime actions?') }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                        <i class="fa-solid fa-trash min-w-8"></i>
                        {{ __('Delete') }}
                    </button>
                </form>
            @endif
        @endcan
    </x-slot>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
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
                - {{ $downtime->getStatusLabel() }}
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
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-{{ $downtime->development_actions }}">
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
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-{{ $downtime->research_actions }}">
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
                                    <p>{!! __('<strong>Skill:</strong> :skill', ['skill' => optional($action->characterSkill)->name]) !!}</p>
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
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg row-span-{{ $downtime->other_actions }}">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                    @foreach ($savedActions as $action)
                        <div>
                            <p class="text-lg">{{ trans_choice('Personal Action|Personal Action :number', count($savedActions), ['number' => ++$actionCount]) }}</p>
                            {!! process_markdown($action->notes) !!}
                            @if ($action->response && $downtime->processed)
                                <p><strong>{{ __('Response') }}:</strong></p>
                                {!! process_markdown($action->response) !!}
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($downtime->response && $downtime->processed)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm lg:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <h3 class="text-xl font-semibold">{{ __('Downtime Response') }}</h3>
                    {!! process_markdown($downtime->response) !!}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
