@php
    use App\Models\Background;
    use App\Models\Character;
    use App\Models\Status;
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
            @if (!empty($character) && $character->isPrimary) <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i> @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @if (!empty($character))
                @include('plotco.partials.approval')
            @endif
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store') }}">
                        @csrf
                        <input type="hidden" name="user_id"
                               value="{{ empty($character) ? auth()->user()->id : $character->user_id }}">
                        @if (!empty($character))
                            <input type="hidden" name="id" value="{{ $character->id }}">
                        @endif
                        <input type="hidden" name="status_id"
                               value="{{ empty($character) ? Status::NEW : $character->status_id }}">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')"/>
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                                              :value="old('name', $character->name ?? '')" required autofocus/>
                                <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                            </div>

                            <div>
                                <x-input-label for="former_rank" :value="__('Former Rank (if applicable)')"/>
                                <x-text-input id="former_rank" name="former_rank" type="text" class="mt-1 block w-full"
                                              :value="old('former_rank', $character->former_rank ?? '')"
                                              :disabled="!empty($character) && Status::NEW != $character->status_id"/>
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
                                <x-input-error class="mt-2" :messages="$errors->get('former_rank')"/>
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
                                            <p>{{ sprintf(__('Training Months: %s'), $background->months) }}</p>
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
                                    <script>
                                        function showBackgroundDescription(backgroundId) {
                                            let backgrounds = document.querySelectorAll('.background-description');
                                            backgrounds.forEach(function (background) {
                                                background.classList.add('hidden');
                                            });
                                            let background = document.getElementById('background-description-' + backgroundId);
                                            background.classList.remove('hidden');
                                        }
                                    </script>
                                    <x-input-error class="mt-2" :messages="$errors->get('background')"/>
                                @else
                                    <x-text-input id="background" name="background" type="text"
                                                  class="mt-1 block w-full"
                                                  :value="$character->background->name" disabled/>
                                    <input type="hidden" name="background_id" value="{{ $character->background_id }}">
                                @endif
                            </div>

                            @include('characters.partials.event-attendance')

                            <div>
                                <x-input-label for="history" :value="__('History')"/>
                                <x-textarea id="history" name="history" rows="12"
                                            class="mt-1 block w-full">{{ $character->history ?? '' }}</x-textarea>
                            </div>

                            <div>
                                <x-input-label for="character_links" :value="__('Pre-Existing Character Links')"/>
                                <p class="text-xs">If you have established background links with other player
                                    characters, please note them here separately.</p>
                                <x-textarea id="character_links" name="character_links" rows="6"
                                            class="mt-1 block w-full">{{ $character->character_links ?? '' }}</x-textarea>
                            </div>

                            @can('edit all characters')
                                <div>
                                    <x-input-label for="plot_notes" :value="__('Plot Notes')"/>
                                    <x-textarea id="plot_notes" name="plot_notes" rows="12"
                                                class="mt-1 block w-full">{{ $character->plot_notes ?? '' }}</x-textarea>
                                </div>
                            @endcan

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Save') }}</x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
