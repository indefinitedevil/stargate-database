@php
    use App\Models\Status;
@endphp
<div class="sm:float-right grid grid-cols-2 sm:flex gap-4 sm:gap-1 mb-6">
    @can('edit', $character)
        @if($character->status_id === Status::NEW)
            <a href="{{ route('characters.ready', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Confirm ready for approval') }}"
            ><i class="fa-solid fa-check"></i>
                <span class="sm:hidden">{{ __('Ready for approval') }}</span>
                <span class="hidden sm:inline">{{ __('Ready') }}</span>
            </a>
        @elseif($character->canBeReset())
            <a href="{{ route('characters.reset', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               onclick="return confirm('{{ __('Are you sure you want to reset this character?') }}')"
               title="{{ __('Reset to new') }}"
            ><i class="fa-solid fa-rotate-left"></i>
                {{ __('Reset') }}
            </a>
        @endif
        @if(!$character->isPrimary)
            <a href="{{ route('characters.primary', ['characterId' => $character->id]) }}"
               class="float-right px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Mark as primary character') }}"
               onclick="return confirm('{{ __('Are you sure you want to mark this character as your primary character?') }}');"
            ><i class="fa-solid fa-star"></i>
                <span class="sm:hidden">{{ __('Make Primary') }}</span>
                <span class="hidden sm:inline">{{ __('Primary') }}</span>
            </a>
        @endif
    @endcan
    @if(!request()->routeIs('characters.view') && !request()->routeIs('characters.view-pretty'))
        <a href="{{ $character->getViewRoute() }}"
           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
           title="{{ __('View') }}"
        ><i class="fa-solid fa-eye"></i> {{ __('View') }}</a>
    @endif
    @can('edit', $character)
        @if(!request()->routeIs('characters.edit'))
            <a href="{{ route('characters.edit', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Edit Character Details') }}"
            >
                <i class="fa-solid fa-pen"></i>
                <span class="sm:hidden">{{ __('Edit Details') }}</span>
                <span class="hidden sm:inline">{{ __('Edit') }}</span>
            </a>
        @endif
        @if(!request()->routeIs('characters.edit-skills'))
            <a href="{{ route('characters.edit-skills', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Edit Skills') }}"
            >
                <i class="fa-solid fa-pen-to-square"></i>
                <span class="sm:hidden">{{ __('Edit Skills') }}</span>
                <span class="hidden sm:inline">{{ __('Skills') }}</span>
            </a>
        @endif
        @if($character->status_id === Status::PLAYED)
            <a href="{{ route('characters.retire', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               onclick="return confirm('{{ __('Are you sure you want to retire this character?') }}');"
               title="{{ __('Retire') }}"
            ><i class="fa-solid fa-bed"></i> {{ __('Retire') }}</a>
            <a href="{{ route('characters.kill', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               onclick="return confirm('{{ __('Are you sure you want to kill this character?') }}')"
               title="{{ __('Kill') }}"
            ><i class="fa-solid fa-skull"></i> {{ __('Kill') }}</a>
        @endif
    @endcan
    @can('delete', $character)
        <a href="{{ route('characters.delete', ['characterId' => $character->id]) }}"
           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
           onclick="return confirm('{{ __('Are you sure you want to delete this character?') }}');"
           title="{{ __('Delete') }}"
        ><i class="fa-solid fa-trash"></i> {{ __('Delete') }}</a>
    @endcan
    @if(!request()->routeIs('characters.logs') && !request()->routeIs('characters.logs-pretty'))
        <a href="{{ $character->getLogsRoute() }}"
           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
           title="{{ __('Logs') }}"
        ><i class="fa-solid fa-clipboard"></i> {{ __('Logs') }}</a>
    @endif
    <a href="{{ route('characters.print', ['characterId' => $character->id]) }}"
       class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
       title="{{ __('Print') }}"
    ><i class="fa-solid fa-print"></i> {{ __('Print') }}</a>
    <a href="{{ route('characters.print-skills', ['characterId' => $character->id]) }}"
       class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
       title="{{ __('Print Skills') }}"
    >
        <i class="fa-regular fa-rectangle-list"></i>
        <span class="sm:hidden">{{ __('Print Skills') }}</span>
        <span class="hidden sm:inline">{{ __('Skills') }}</span></a>
</div>
