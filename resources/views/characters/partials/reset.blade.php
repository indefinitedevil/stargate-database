@if($character->canBeReset())
    <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <x-link-button href="{{ route('characters.reset', ['characterId' => $character->id]) }}"
                           class="float-right ml-1"
                           onclick="return confirm('{{ __('Are you sure you want to reset this character?') }}');"
                           title="{{ __('Reset to new') }}"
            ><i class="fa-solid fa-rotate-left"></i>
                {{ __('Reset') }}
            </x-link-button>
            <h3 class="text-lg font-semibold">{{ __('Reset available') }}</h3>
            <div class="space-y-2">
                <p>{{ __('You can optionally reset your character to the pre-approval state. This allows you to make any changes you might want after playing your character for the first time.') }}</p>
                <p>{{ __('This option will become unavailable when you save your first downtime submission for this character.') }}</p>
                @php
                    @endphp
                @if ($character->trainingMonths < $character->background->adjustedMonths)
                    <p>
                        <strong><em>{{ __('Your character has been built with less than the current amount of training months. Resetting will allow you to use those extra months.') }}</em></strong>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endif
