@if($character->canBeReset())
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div class="mt-1">
            <h3 class="text-lg font-semibold">{{ __('Reset available') }}</h3>
            <a href="{{ route('characters.reset', ['characterId' => $character->id]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               onclick="return confirm('{{ __('Are you sure you want to reset this character?') }}');"
               title="{{ __('Reset to new') }}"
            ><i class="fa-solid fa-rotate-left"></i>
                {{ __('Reset') }}
            </a>

            <p>{{ __('You can reset your character to the pre-approval state. This allows you to make any changes you might want after playing your character for the first time.') }}</p>
            <p>{{ __('This option will become unavailable when you start your first downtime submission for this character.') }}</p>
        </div>
    </div>
@endif
