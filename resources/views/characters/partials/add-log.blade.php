@php
    use App\Models\SpecialtyType;
@endphp
@can('edit all characters')
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <form method="POST" action="{{ route('characters.store-log') }}" id="add-log">
            <p class="text-xl">{{ empty($editLog) ? __('Add new log') : __('Edit log') }}</p>
            @csrf
            @include('partials.errors')
            @if (!empty($editLog))
                <input type="hidden" name="log_id" value="{{ $editLog->id }}">
                <input type="hidden" name="character_skill_id" value="{{ $editLog->character_skill_id }}">
            @endif
            <input type="hidden" name="character_id" value="{{$character->id }}">
            <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
                <div class="col-span-3">
                    <x-input-label for="skill">{{ !empty($editLog) ? __('Skill') : __('New Skill') }}</x-input-label>
                    <x-select id="skill" name="skill_id" class="mt-1 block w-full" required>
                        @if (!empty($editLog))
                            <option value="{{ $editLog->characterSkill->skill_id }}" selected="selected">
                                {{ __(':name (:cost months)', ['name' => $editLog->characterSkill->name, 'cost' => $editLog->characterSkill->cost]) }}
                            </option>
                        @else
                            <option value="">{{ __('Select a skill') }}</option>
                        @endif
                        @include('characters.partials.available-skills')
                    </x-select>
                    <x-input-error class="mt-2" :messages="$errors->get('skill_id')" />
                </div>

                <div class="col-span-3">
                    <x-input-label for="specialty">{{ __('Specialty') }}</x-input-label>
                    <x-select id="specialty" name="specialty_id[]" class="mt-1 block w-full"
                              :multiple="true">
                        @foreach (SpecialtyType::all() as $specialtyType)
                            <optgroup label="{{ $specialtyType->name }}">
                                @foreach ($specialtyType->skillSpecialties->sortBy('name') as $specialty)
                                    <option value="{{ $specialty->id }}"
                                            @if(!empty($editLog) && $editLog->characterSkill->skillSpecialties->contains($specialty)) selected @endif
                                    >
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </x-select>
                    <p class="text-xs">
                        {{ __('Press Ctrl to select/de-select additional specialties.') }}
                    </p>
                    <x-input-error class="mt-2" :messages="$errors->get('specialty_id')" />
                </div>

                <div>
                    <x-input-label for="body_change" :value="__('Body Change')"/>
                    <x-text-input id="body_change" name="body_change" type="number" class="mt-1 block w-full"
                                  :value="old('body_change', empty($editLog) ? 0 : $editLog->body_change)" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('body_change')"/>
                </div>

                <div>
                    <x-input-label for="vigor_change" :value="__('Vigor Change')"/>
                    <x-text-input id="vigor_change" name="vigor_change" type="number" class="mt-1 block w-full"
                                  :value="old('vigor_change', empty($editLog) ? 0 : $editLog->vigor_change)"
                                  required/>
                    <x-input-error class="mt-2" :messages="$errors->get('vigor_change')"/>
                </div>

                <div>
                    <x-input-label for="temp_body_change" :value="__('Temporary Body Change')"/>
                    <x-text-input id="temp_body_change" name="temp_body_change" type="number"
                                  class="mt-1 block w-full"
                                  :value="old('temp_body_change', empty($editLog) ? 0 : $editLog->temp_body_change)"
                                  required/>
                    <x-input-error class="mt-2" :messages="$errors->get('temp_body_change')"/>
                </div>

                <div>
                    <x-input-label for="temp_vigor_change" :value="__('Temporary Vigor Change')"/>
                    <x-text-input id="temp_vigor_change" name="temp_vigor_change" type="number"
                                  class="mt-1 block w-full"
                                  :value="old('temp_vigor_change', empty($editLog) ? 0 : $editLog->temp_vigor_change)"
                                  required/>
                    <x-input-error class="mt-2" :messages="$errors->get('temp_vigor_change')"/>
                </div>

                <div>
                    <x-input-label for="amount_trained" :value="__('Amount Trained')"/>
                    <x-text-input id="amount_trained" name="amount_trained" type="number" class="mt-1 block w-full"
                                  :value="old('amount_trained', empty($editLog) ? 0 : $editLog->amount_trained)"
                                  required/>
                    <x-input-error class="mt-2" :messages="$errors->get('amount_trained')"/>
                </div>

                <div>
                    <x-input-label for="completed">
                        {{ __('Completed') }}
                        <input type="checkbox" id="completed" name="completed" class="" value="1"
                               @if (!empty($editLog) && $editLog->characterSkill->completed) checked="checked" @endif
                        />
                    </x-input-label>
                </div>

                <div class="col-span-3">
                    <x-input-label for="notes" :value="__('Notes (player-visible)')"/>
                    <x-textarea id="notes" name="notes" class="mt-1 block w-full" required>{{ empty($editLog) ? '' : $editLog->notes }}</x-textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('notes')"/>
                </div>

                <div class="col-span-3">
                    <x-input-label for="plot_notes" :value="__('Plot notes')"/>
                    <x-textarea id="plot_notes" name="plot_notes" class="mt-1 block w-full">{{ empty($editLog) ? '' : $editLog->plot_notes }}</x-textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('plot_notes')"/>
                </div>
            </div>

            <div class="flex items-center mt-4">
                <x-primary-button>{{ __(!empty($editLog) ? 'Save Log' : 'Add Log') }}</x-primary-button>
            </div>
        </form>
    </div>
@endcan
