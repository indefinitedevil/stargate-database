<x-app-layout>
    <x-slot name="title">{{ __('Plot Logs') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Plot Logs') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
            {{ __('Plot Logs') }}
        </h2>
        <p>{{ __('Logs are records of changes made to characters. These logs are those made by a plot coordinator.') }}</p>
    </div>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="grid grid-cols-1 sm:grid-cols-4 clear-both gap-6">
            @foreach ($logs as $log)
                <div>
                    @can ('edit all characters')
                        <a href="{{ route('characters.edit-log', ['characterId' => $log->character, 'logId' => $log]) }}"
                           class="underline float-right">
                            <i class="fa-solid fa-pen-to-square" title="{{ __('Edit log') }}"></i>
                            {{ __('Edit') }}
                        </a>
                    @endcan
                    <p>{{ __('Date: :date', ['date' => format_datetime($log->created_at, 'j M Y')]) }}</p>
                    <p>{{ __('Character: :character', ['character' => $log->character->listName]) }}</p>
                    <p>{{ __('Player: :player', ['player' => $log->character->user->name]) }}</p>
                    <p>{{ __('Type: :type', ['type' => $log->logType->name]) }}</p>
                    <p>{{ __('Skill: :skill', ['skill' => $log->skill->name]) }}</p>
                    @if (0 != $log->amount_trained)
                        <p>{{ __('Trained: :amount months', ['amount' => add_positive_modifier($log->amount_trained)]) }}</p>
                    @endif
                    @if (0 != $log->body_change)
                        <p>{{ __('Body: :amount', ['amount' => add_positive_modifier($log->body_change)]) }}</p>
                    @endif
                    @if (0 != $log->vigor_change)
                        <p>{{ __('Vigor: :amount', ['amount' => add_positive_modifier($log->vigor_change)]) }}</p>
                    @endif
                    @if (0 != $log->temp_body_change)
                        <p>{{ __('Temp Body: :amount', ['amount' => add_positive_modifier($log->temp_body_change)]) }}</p>
                    @endif
                    @if (0 != $log->temp_vigor_change)
                        <p>{{ __('Temp Vigor: :amount', ['amount' => add_positive_modifier($log->temp_vigor_change)]) }}</p>
                    @endif
                    <p>{{ __('Notes: :notes', ['notes' => $log->notes]) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
