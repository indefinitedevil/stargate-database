@php
    use App\Models\Status;
@endphp
<div class="float-right mb-6">
    <x-dropdown align="right" contentClasses="py-1 mt-2 bg-white dark:bg-gray-700 divide-y divide-gray-100">
        <x-slot name="trigger">
            <button type="button"
                    class="inline-flex items-center p-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-1">
                {{ __('Actions') }}

                <span class="ms-1 -mr-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </span>
            </button>
        </x-slot>

        <x-slot name="content">
            <div>
                @can('edit', $character)
                    @if($character->status_id === Status::NEW)
                        <x-dropdown-link :href="route('characters.ready', ['characterId' => $character->id])"
                                         title="{{ __('Confirm ready for approval') }}"
                        >
                            <i class="fa-solid fa-check min-w-8"></i>
                            {{ __('Mark ready for approval') }}
                        </x-dropdown-link>
                    @elseif($character->canBeReset())
                        <x-dropdown-link :href="route('characters.reset', ['characterId' => $character->id])"
                                         onclick="return confirm('{{ __('Are you sure you want to reset this character?') }}')"
                                         title="{{ __('Reset to new') }}"
                        >
                            <i class="fa-solid fa-rotate-left min-w-8"></i>
                            {{ __('Reset') }}
                        </x-dropdown-link>
                    @endif
                    @if(!$character->isPrimary)
                        <x-dropdown-link :href="route('characters.primary', ['characterId' => $character->id])"
                                         title="{{ __('Mark as primary character') }}"
                                         onclick="return confirm('{{ __('Are you sure you want to mark this character as your primary character?') }}');"
                        >
                            <i class="fa-solid fa-star min-w-8"></i>
                            {{ __('Make Primary') }}
                        </x-dropdown-link>
                    @endif
                @endcan
                @if(!request()->routeIs('characters.view') && !request()->routeIs('characters.view-pretty'))
                    <x-dropdown-link :href="$character->getViewRoute()"
                                     title="{{ __('View') }}"
                    >
                        <i class="fa-solid fa-eye min-w-8"></i>
                        {{ __('View') }}
                    </x-dropdown-link>
                @endif
                @can('edit', $character)
                    @if(!request()->routeIs('characters.edit'))
                        <x-dropdown-link :href="route('characters.edit', ['characterId' => $character->id])"
                                         title="{{ __('Edit Character Details') }}"
                        >
                            <i class="fa-solid fa-pen min-w-8"></i>
                            {{ __('Edit Details') }}
                        </x-dropdown-link>
                    @endif
                    @if(!request()->routeIs('characters.edit-skills'))
                        <x-dropdown-link :href="route('characters.edit-skills', ['characterId' => $character->id])"
                                         title="{{ __('Edit Skills') }}"
                        >
                            <i class="fa-solid fa-pen-to-square min-w-8"></i>
                            {{ __('Edit Skills') }}
                        </x-dropdown-link>
                    @endif
                @endcan
                @if(!request()->routeIs('characters.logs') && !request()->routeIs('characters.logs-pretty') && $character->status_id > Status::READY)
                    <x-dropdown-link :href="$character->getLogsRoute()"
                                     title="{{ __('Character Logs') }}"
                    >
                        <i class="fa-solid fa-clipboard min-w-8"></i>
                        {{ __('Character Logs') }}
                    </x-dropdown-link>
                @endif
                <x-dropdown-link :href="route('characters.print', ['characterId' => $character->id])"
                                 title="{{ __('Print Sheet') }}"
                >
                    <i class="fa-solid fa-print min-w-8"></i>
                    {{ __('Print Sheet') }}
                </x-dropdown-link>
                <x-dropdown-link :href="route('characters.print-skills', ['characterId' => $character->id])"
                                 title="{{ __('Print Skills') }}"
                >
                    <i class="fa-regular fa-rectangle-list min-w-8"></i>
                    {{ __('Print Skills') }}
                </x-dropdown-link>
            </div>
            <div>
                @if($character->status_id === Status::PLAYED)
                    <x-dropdown-link :href="route('characters.retire', ['characterId' => $character->id])"
                                     onclick="return confirm('{{ __('Are you sure you want to retire this character?') }}');"
                                     title="{{ __('Retire') }}"
                    >
                        <i class="fa-solid fa-bed min-w-8"></i>
                        {{ __('Retire') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('characters.kill', ['characterId' => $character->id])"
                                     onclick="return confirm('{{ __('Are you sure you want to kill this character?') }}')"
                                     title="{{ __('Kill') }}"
                    >
                        <i class="fa-solid fa-skull min-w-8"></i>
                        {{ __('Kill') }}
                    </x-dropdown-link>
                @endif
                @can('delete', $character)
                    <x-dropdown-link :href="route('characters.delete', ['characterId' => $character->id])"
                                     onclick="return confirm('{{ __('Are you sure you want to delete this character?') }}');"
                                     title="{{ __('Delete') }}"
                    >
                        <i class="fa-solid fa-trash min-w-8"></i>
                        {{ __('Delete') }}
                    </x-dropdown-link>
                @endcan
            </div>
            @can('edit all characters')
                <div>
                    @can('played', $character)
                        <x-dropdown-link :href="route('characters.played', ['characterId' => $character->id])"
                                         onclick="return confirm('{{ __('Are you sure you want to mark this character as played?') }}');"
                                         title="{{ __('Played') }}"
                        >
                            <i class="fa-solid fa-play min-w-8"></i>
                            {{ __('Played') }}
                        </x-dropdown-link>
                    @endcan
                    @can('resuscitate', $character)
                        <x-dropdown-link :href="route('characters.resuscitate', ['characterId' => $character->id])"
                                         onclick="return confirm('{{ __('Are you sure you want to resuscitate this character?') }}');"
                                         title="{{ __('Resuscitate') }}"
                        >
                            <i class="fa-solid fa-kit-medical min-w-8"></i>
                            {{ __('Resuscitate') }}
                        </x-dropdown-link>
                    @endcan
                    @can('inactive', $character)
                        <x-dropdown-link :href="route('characters.inactive', ['characterId' => $character->id])"
                                         onclick="return confirm('{{ __('Are you sure you want to mark this character as inactive?') }}');"
                                         title="{{ __('Inactive') }}"
                        >
                            <i class="fa-solid fa-power-off min-w-8"></i>
                            {{ __('Inactive') }}
                        </x-dropdown-link>
                    @endcan
                </div>
            @endcan
        </x-slot>
    </x-dropdown>
</div>
