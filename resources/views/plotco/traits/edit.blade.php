@php
    use App\Models\CharacterTrait;
    $title = empty($trait->id) ? __('Create character trait') : sprintf(__('Edit character trait: %s'), $trait->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <div>
            <form method="POST" action="{{ route('plotco.traits.store') }}">
                @csrf
                @if (!empty($trait->id))
                    <input type="hidden" name="id" value="{{ $trait->id }}">
                @endif
                <div class="sm:grid sm:grid-cols-6 gap-6 space-y-2 sm:space-y-0">
                    <div class="col-span-2">
                        <x-input-label for="name" :value="__('Name')"/>
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                      :value="old('name', $trait->name ?? '')" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div>
                        @if (!empty($trait->icon))
                            <div class="float-right">
                                <i class="fa-solid {{ $trait->icon }}"></i>
                            </div>
                        @endif
                        <x-input-label for="icon" :value="__('Icon')"/>
                        <x-text-input id="icon" name="icon" type="text" class="mt-1 block w-full"
                                      :value="old('icon', $trait->icon ?? '')" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('icon')"/>
                        <p class="text-xs">{!! __('You can use any of <a href=":url" class="underline" target="_blank">the Font Awesome icons</a>', ['url' => 'https://fontawesome.com/search?o=r&s=solid&ip=classic']) !!}</p>
                    </div>

                    <div>
                        <x-input-label for="chance" :value="__('Chance')"/>
                        <x-text-input id="chance" name="chance" type="number" class="mt-1 block w-full" min="0"
                                      max="100" step="1"
                                      :value="old('chance', $trait->chance ?? 0)" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('chance')"/>
                        <p class="text-xs mt-1">{{ __('There is a 1 in X chance of a character getting it randomly.') }}</p>
                    </div>
                    <div>
                        <x-input-label for="revealed" :value="__('Revealed')"/>
                        <x-checkbox-input id="revealed" name="revealed"
                                          :value="1"
                                          :checked="(bool) ($trait->revealed ?? false)"/>
                        <x-input-error class="mt-2" :messages="$errors->get('revealed')"/>
                        <p class="text-xs mt-1">{{ __('This currently does nothing.') }}</p>
                    </div>

                    <div class="col-span-3">
                        <x-input-label for="description" :value="__('Description')"/>
                        <x-textarea id="description" name="description" rows="6"
                                    class="mt-1 block w-full">{{ $trait->description ?? '' }}</x-textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                        <p class="text-xs mt-1">{!! __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) !!}</p>
                        <p class="text-xs mt-1">{{ __('This field is not visible to players.') }}</p>
                    </div>

                    <div class="col-span-3">
                        <p class="text-xl">{{ __('Trait Masks') }}</p>
                        <p class="text-sm mt-1">{{ __('These are used as random symbols to mask the traits on character sheets. Do not pick these for icons.') }}</p>
                        <ul class="sm:grid grid-cols-3 gap-2 mt-2">
                            @foreach (CharacterTrait::TRAIT_MASKS as $mask)
                                <li><i class="fa-solid {{ $mask }}"></i> {{ $mask }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    @if (!empty($trait->id))
                        <x-link-button :primary="false"
                                       onclick="return confirm('{{ __('Are you sure you want to delete this trait?') }}')"
                                       href="{{ route('plotco.traits.delete', $trait) }}">{{ __('Delete') }}</x-link-button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if (!empty($trait->id))
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                <h3 class="text-2xl">{{ __('Characters') }}</h3>
                <ul class="list-disc list-inside space-y-2 sm:space-y-0 sm:grid grid-cols-3 gap-2">
                    @if ($trait->characters->isEmpty())
                        <li>{{ __('No characters have this trait') }}</li>
                    @else
                        @foreach($trait->characters as $character)
                            <li>
                                <a href="{{ route('characters.view', ['characterId' => $character->id]) }}"
                                   class="underline">{{ $character->listName }}</a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    @endif
</x-app-layout>
