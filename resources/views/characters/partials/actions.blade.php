@php
    use App\Models\Status;
@endphp
<div class="sm:float-right grid grid-cols-2 sm:flex gap-4 sm:gap-1 mb-6">
    @can('edit', $character)
        @if($character->status_id === Status::NEW)
            <a href="{{ route('characters.ready', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Confirm ready for approval') }}"
            ><i class="fa-solid fa-check"></i><span class="sm:hidden"> {{ __('Ready for approval') }}</span></a>
        @endif
    @endcan
    @if(!request()->routeIs('characters.view'))
        <a href="{{ route('characters.view', ['characterId' => $character->id]) }}"
           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
           title="{{ __('View') }}"
        ><i class="fa-solid fa-eye"></i><span class="sm:hidden"> {{ __('View') }}</span></a>
    @endif
    @can('edit', $character)
        @if(!request()->routeIs('characters.edit'))
            <a href="{{ route('characters.edit', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Edit Character Details') }}"
            ><i class="fa-solid fa-pen"></i><span class="sm:hidden"> {{ __('Edit Details') }}</span></a>
        @endif
        @if(!request()->routeIs('characters.edit-skills'))
            <a href="{{ route('characters.edit-skills', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               title="{{ __('Edit Skills') }}"
            ><i class="fa-solid fa-pen-to-square"></i><span class="sm:hidden"> {{ __('Edit Skills') }}</span></a>
        @endif
        @if($character->status_id === Status::PLAYED)
            <a href="{{ route('characters.retire', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               onclick="return confirm({{ __('Are you sure you want to retire this character?') }}"
               title="{{ __('Retire') }}"
            ><i class="fa-solid fa-bed"></i><span class="sm:hidden"> {{ __('Retire') }}</span></a>
            <a href="{{ route('characters.kill', ['characterId' => $character->id]) }}"
               class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
               onclick="return confirm({{ __('Are you sure you want to kill this character?') }})"
               title="{{ __('Kill') }}"
            ><i class="fa-solid fa-skull"></i><span class="sm:hidden"> {{ __('Kill') }}</span></a>
        @endif
    @endcan
    @can('delete', $character)
        <a href="{{ route('characters.delete', ['characterId' => $character->id]) }}"
           class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
           onclick="return confirm({{ __('Are you sure you want to delete this character?') }})"
           title="{{ __('Delete') }}"
        ><i class="fa-solid fa-trash"></i><span class="sm:hidden"> {{ __('Delete') }}</span></a>
    @endcan
    <a href="{{ route('characters.print', ['characterId' => $character->id]) }}"
       class="px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1"
       title="{{ __('Print') }}"
    ><i class="fa-solid fa-print"></i><span class="sm:hidden"> {{ __('Print') }}</span></a>
</div>
