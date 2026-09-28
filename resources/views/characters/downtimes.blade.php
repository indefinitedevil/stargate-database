@php use App\Models\ActionType; @endphp
<x-app-layout>
    <x-slot name="title">{{ sprintf(__('Character Downtimes: %s'), $character->name) }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ sprintf(__('Character Downtimes: %s'), $character->name) }}
            @if($character->isPrimary)
                <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i>
            @endif
        </h2>
    </x-slot>
    @include('characters.partials.sidebar2')

    @include('characters.partials.missing-specialties')
    @include('plotco.partials.approval')
    @include('characters.partials.reset')
    @include('characters.partials.details')

    @foreach ($downtimes as $downtime)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
            <div>
                <strong><a href="{{ route('downtimes.view', ['characterId' => $character, 'downtimeId' => $downtime]) }}"
                           class="underline">{{ $downtime->name }}</a></strong>
                <p>{{ __('Date: :start - :end', ['start' => format_datetime($downtime->start_time, 'j M Y'), 'end' => format_datetime($downtime->end_time, 'j M Y')]) }}</p>
                @if ($downtime->event)
                    <p>{{ __('Event: :event', ['event' => $downtime->event->name]) }}</p>
                @endif
                <p>{{ __('Status: :status', ['status' => $downtime->getStatusLabel()]) }}</p>

            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 clear-both gap-4 mt-2">
                @foreach($downtimeActions[$downtime->id] as $action)
                    <div @if (ActionType::ACTION_OTHER == $action->action_type_id)class="col-span-2"@endif>
                        <p>{{ __('Action: :action', ['action' => $action->actionType?->name]) }}</p>
                        @if ($action->characterSkill)
                            <p>{{ __('Skill: :skill', ['skill' => $action->characterSkill->name]) }}</p>
                        @endif
                        @if (in_array($action->action_type_id, [ActionType::ACTION_RESEARCHING, ActionType::ACTION_RESEARCH_SUBJECT]))
                            <p>{{ __('Research Project: :research', ['research' => $action->researchProject?->name]) }}</p>
                        @endif
                        @if ($action->notes)
                            <p>{{ __('Notes: :notes', ['notes' => $action->notes]) }}</p>
                        @endif
                        @if ($downtime->processed && $action->response)
                            <p class="mt-4">{{ __('Response: :response', ['response' => $action->response]) }}</p>
                        @endif
                    </div>

                @endforeach
            </div>
        </div>
    @endforeach

    <div class="mt-6">
        {{ $downtimes->links() }}
    </div>
</x-app-layout>
