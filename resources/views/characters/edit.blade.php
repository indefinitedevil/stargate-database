@php
    use App\Models\Background;
    use App\Models\Character;
    use App\Models\Status;
    use App\Models\User;
    $title = empty($character) ? __('Create character') : sprintf(__('Edit character: %s'), $character->name);
@endphp
<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="header">
        @if (!empty($character))
            @include('characters.partials.actions')
        @endif
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
            @if (!empty($character) && $character->isPrimary)
                <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i>
            @endif
        </h2>
    </x-slot>

    @if (!empty($character))
        @include('plotco.partials.approval')
        @include('characters.partials.reset')
    @endif
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <form method="POST" action="{{ route('characters.store') }}">
            @csrf
            @if (!empty($character))
                <input type="hidden" name="id" value="{{ $character->id }}">
            @endif
            <input type="hidden" name="status_id"
                   value="{{ empty($character) ? Status::NEW : $character->status_id }}">
            <div class="grid grid-cols-1 gap-6">
                @can ('edit all characters')
                    <div>
                        <x-input-label for="user_id" :value="__('User')"/>
                        <x-select id="user_id" name="user_id" class="mt-1 block w-full" required>
                            @foreach (User::all() as $user)
                                <option value="{{ $user->id }}"
                                        @if(!empty($character) && $user->id === $character->user_id || empty($character) && auth()->user()->id == $user->id) selected @endif >
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                @else
                    <input type="hidden" name="user_id"
                           value="{{ empty($character) ? auth()->user()->id : $character->user_id }}">
                @endcan
                <div>
                    <x-input-label for="name" :value="__('Full Name')"/>
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                  :value="old('name', $character->name ?? '')" required autofocus/>
                    <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    <div>
                        <p class="text-xs mt-1">
                            {!! __('Feeling stuck? <a class="underline cursor-pointer" onclick="generateRandomNames()">Generate some random names</a>') !!}
                        </p>
                        <div id="randomNames" class="grid grid-cols-1 sm:grid-cols-2"></div>
                    </div>
                </div>

                <div>
                    <x-input-label for="short_name" :value="__('Short Name (optional)')"/>
                    <p class="text-xs">
                        {{ __('This will be used on indexes and your printed sheet. This is intended for condensing long character names into a simpler format.') }}
                    </p>
                    <p class="text-xs">
                        {{ __('This is not for nicknames, but so you can have "Robert John Smith" as your full name and "Bob Smith" as the short name people actually know you by.') }}
                    </p>
                    <x-text-input id="short_name" name="short_name" type="text" class="mt-1 block w-full"
                                  :value="old('short_name', $character->short_name ?? '')"/>
                    <x-input-error class="mt-2" :messages="$errors->get('short_name')"/>
                </div>

                @can('edit all characters')
                    <div>
                        <x-input-label for="rank" :value="__('Rank')"/>
                        <x-text-input id="rank" name="rank" type="text" class="mt-1 block w-full"
                                      :value="old('rank', $character->rank ?? '')"/>
                        <x-input-error class="mt-2" :messages="$errors->get('rank')"/>
                    </div>
                @endcan

                <div>
                    <x-input-label for="former_rank" :value="__('Former Rank (optional)')"/>
                    <x-text-input id="former_rank" name="former_rank" type="text" class="mt-1 block w-full"
                                  :value="old('former_rank', $character->former_rank ?? '')"
                                  :disabled="!empty($character) && Status::READY < $character->status_id"/>
                    <x-input-error class="mt-2" :messages="$errors->get('former_rank')"/>
                </div>

                <div class="flex gap-4">
                    <p>{{ __('Are you a hero or a scoundrel?') }}</p>
                    <x-input-label for="hero" class="text-lg">
                        <x-radio-input id="hero" name="hero_scoundrel" class=""
                                       value="{{ Character::HERO }}"
                                       :checked="old('hero_scoundrel', $character->hero_scoundrel ?? 0) === Character::HERO"
                                       :disabled="!empty($character) && Status::READY < $character->status_id"/>
                        {{ __('Hero') }}
                    </x-input-label>
                    <x-input-label for="scoundrel" class="text-lg">
                        <x-radio-input id="scoundrel" name="hero_scoundrel" class=""
                                       value="{{ Character::SCOUNDREL }}"
                                       :checked="old('hero_scoundrel', $character->hero_scoundrel ?? 0) === Character::SCOUNDREL"
                                       :disabled="!empty($character) && Status::READY < $character->status_id"/>
                        {{ __('Scoundrel') }}
                    </x-input-label>
                    <x-input-error class="mt-2" :messages="$errors->get('hero_scoundrel')"/>
                </div>

                <div>
                    <x-input-label for="background" :value="__('Background')"/>
                    @if (empty($character) || Status::NEW === $character->status_id)
                        <x-select id="background" name="background_id" class="mt-1 block w-full" required
                                  onchange="showBackgroundDescription(this.value)">
                            @if (empty($character))
                                <option value="">{{ __('Select a background') }}</option>
                            @endif
                            @foreach(Background::all() as $background)
                                <option value="{{ $background->id }}"
                                        @if(!empty($character) && $background->id === $character->background_id) selected @endif >
                                    {{ $background->name }}
                                </option>
                            @endforeach
                        </x-select>
                        @foreach(Background::all() as $key => $background)
                            <div id="background-description-{{ $background->id }}"
                                 class="background-description p-2 mt-2 space-y-2 border-2 border-slate-600 rounded-md @if(empty($character) || $background->id != $character->background_id) hidden @endif">
                                <p>{{ $background->description }}</p>
                                <p>{{ sprintf(__('Training Months: %s'), $background->adjustedMonths) }}</p>
                                <div>
                                    <p class="text-lg font-medium">{{ __('Starting Skills') }}</p>
                                    <ul class="grid grid-cols-2 sm:grid-cols-6">
                                        @foreach ($background->skills as $skill)
                                            <li>{{ $skill->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                        <x-input-error class="mt-2" :messages="$errors->get('background')"/>
                    @else
                        <x-text-input id="background" name="background" type="text"
                                      class="mt-1 block w-full"
                                      :value="$character->background->name" disabled/>
                        <input type="hidden" name="background_id" value="{{ $character->background_id }}">
                    @endif
                </div>

                <div>
                    <x-input-label for="history" :value="__('History')"/>
                    <p class="text-xs">
                        {{ __('This remains editable after character creation.') }}
                    </p>
                    <x-textarea id="history" name="history" rows="12"
                                class="mt-1 block w-full">{{ $character->history ?? '' }}</x-textarea>
                </div>

                <div>
                    <x-input-label for="character_links" :value="__('Pre-Existing Character Links')"/>
                    <p class="text-xs">
                        {{ __('If you have established background links with other player characters, please note them here separately.') }}
                    </p>
                    <p class="text-xs">
                        {{ __('This remains editable after character creation.') }}
                    </p>
                    <x-textarea id="character_links" name="character_links" rows="6"
                                class="mt-1 block w-full">{{ $character->character_links ?? '' }}</x-textarea>
                </div>

                @can('edit all characters')
                    <div>
                        <x-input-label for="plot_notes" :value="__('Plot Notes')"/>
                        <x-textarea id="plot_notes" name="plot_notes" rows="12"
                                    class="mt-1 block w-full">{{ $character->plot_notes ?? '' }}</x-textarea>
                    </div>

                    <div>
                        <x-input-label for="other_abilities" :value="__('Other Abilities')"/>
                        <x-textarea id="other_abilities" name="other_abilities" rows="12"
                                    class="mt-1 block w-full">{{ $character->other_abilities ?? '' }}</x-textarea>
                        <p class="text-sm">{{ __('Use <a href=":url" class="underline" target="_blank">Markdown formatting</a> to style.', ['url' => 'https://www.markdownguide.org/cheat-sheet/']) }}</p>
                    </div>
                @endcan

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </div>
        </form>
    </div>
    <script src="{{ asset('js/characters.js') }}" defer></script>
</x-app-layout>
