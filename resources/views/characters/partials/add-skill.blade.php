@php
    use App\Models\Status;
@endphp
<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
    <form method="POST" action="{{ route('characters.store-skill') }}" id="add-skill">
        <p class="text-xl">{{ empty($editSkill) ? __('Add new skill') : __('Edit skill: :skill', ['skill' => $editSkill->name]) }}</p>
        @csrf
        @include('partials.errors')
        @if (!empty($editSkill))
            <input type="hidden" name="id" value="{{ $editSkill->id }}">
        @endif
        @php
            global $skills;
            $skills = [];
        @endphp
        <input type="hidden" name="character_id" value="{{$character->id }}">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div class="">
                    <x-input-label for="skill">{{ __('Skill') }}</x-input-label>
                    @if (!empty($editSkill) && $editSkill->completed)
                        @php
                            $skills[] = $editSkill->skill;
                        @endphp
                        <input type="hidden" name="skill_id" value="{{$editSkill->skill_id }}">
                        <x-text-input class="mt-1 block w-full" value="{{ $editSkill->name }}"
                                      disabled/>
                    @else
                        <x-select id="skill" name="skill_id" class="mt-1 block w-full" required
                                  onchange="showSkillDescription(this.value)">
                            @if (!empty($editSkill))
                                @php
                                    $skills[] = $editSkill->skill;
                                @endphp
                                <option value="{{ $editSkill->skill_id }}" selected="selected">
                                    {{ __(':name (:cost months)', ['name' => $editSkill->name, 'cost' => $editSkill->cost]) }}
                                </option>
                            @else
                                <option value="">{{ __('Select a skill') }}</option>
                            @endif
                            @include('characters.partials.available-skills')
                        </x-select>
                    @endif
                    @if (!empty($editSkill))
                        <p class="text-xs">
                            {{ __('If the selected skill has specialties, you will need to edit the skill after saving in order to choose your specialties.') }}
                        </p>
                    @endif
                </div>

                @if (!empty($editSkill) && $editSkill->skill->specialties > 0)
                    <div>
                        <x-input-label for="specialty">{{ __('Specialty') }}
                            ({{ $editSkill->skill->specialties }})
                        </x-input-label>
                        <x-select id="specialty" name="specialty_id[]" class="mt-1 block w-full"
                                  :multiple="$editSkill->skill->specialties > 1">
                            @foreach ($editSkill->skill->specialtyType->skillSpecialties->sortBy('name') as $specialty)
                                <option value="{{ $specialty->id }}"
                                        @if($editSkill->skillSpecialties->contains($specialty)) selected @endif
                                >
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </x-select>
                        @if ($editSkill->skill->specialties > 1)
                            <p class="text-xs">{{ __('Press Ctrl to select/de-select additional specialties.') }}</p>
                        @endif
                    </div>
                @endif

                @if (!empty($editSkill) && ($editSkill->discountAvailable || $editSkill->discountedBy->count()))
                    <div>
                        <x-input-label
                            for="discounted_by">{{ __('Available discounts') }}</x-input-label>
                        <x-select id="discounted_by" name="discounted_by[]" class="mt-1 block w-full"
                                  multiple>
                            <option value="">{{ __('No discount') }}</option>
                            @foreach ($editSkill->discountedBy as $discountingSkill)
                                <option value="{{ $discountingSkill->id }}" selected>
                                    {{ sprintf(__('%s (-%s months)'), $discountingSkill->name, $discountingSkill->discountFor($editSkill->skill_id)) }}
                                </option>
                            @endforeach
                            @foreach ($editSkill->discountsAvailable as $discountingSkill)
                                <option value="{{ $discountingSkill->id }}"
                                        @if($editSkill->discountedBy->contains(['id' => $discountingSkill->id])) selected @endif
                                >
                                    {{ $discountingSkill->name }}
                                </option>
                            @endforeach
                        </x-select>
                    </div>
                @elseif (empty($editSkill))
                    <div>
                        <p>{{ __('To apply a discount, first save the skill, then edit the saved skill to select applicable discounts.') }}</p>
                    </div>
                @endif

                @if (Status::APPROVED > $character->status_id)
                    <div>
                        <x-input-label for="completed">
                            {{ __('Completed') }}
                            <input type="checkbox" id="completed" name="completed" class="" value="1"
                                   @if (!empty($editSkill) && $editSkill->completed || Status::NEW == $character->status_id) checked="checked" @endif
                            />
                        </x-input-label>
                        <p class="text-xs">
                            {{ __('Mark as completed if you are buying the full skill, or leave it unchecked if you are only partially investing into it.') }}
                        </p>
                        <p class="text-xs">
                            {{ __('You can have one unfinished skill at the end of character creation - any remaining months will be assigned to it.') }}
                        </p>
                    </div>
                @endif

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __(!empty($editSkill) ? 'Save Skill' : 'Add Skill') }}</x-primary-button>
                </div>
            </div>
            <div>
                @foreach ($skills as $skill)
                    <div id="skill-description-{{ $skill->id }}"
                         class="skill-description mt-1 @if (empty($editSkill) || $editSkill->skill->id != $skill->id) hidden @endif">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $skill->name }}</h3>
                        <div class="space-y-1">
                            {!! process_markdown($skill->description) !!}
                        </div>
                        @if ($skill->specialties)
                            <div class="mt-1">
                                <p>{{ __('To choose specialties, first save the skill, then edit the saved skill to select applicable specialties.') }}</p>
                            </div>
                        @endif
                        @if ($skill->feats->count())
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Feats') }}</h4>
                            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach ($skill->feats as $feat)
                                    <li>
                    <span class="underline decoration-dashed underline-offset-4"
                          onclick="toggleVisibility('feat-{{$skill->id}}-{{ $feat->id }}')">
                    {{ $feat->name }}
                    <i class="fa-regular fa-circle-question"
                       title="{{ $feat->description }}"
                    ></i>
                    </span>
                                        <span id="feat-{{$skill->id}}-{{ $feat->id }}"
                                              class="text-sm hidden pl-4">
                        {!! process_markdown($feat->description) !!}
                    </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($skill->cards->count())
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Cards') }}</h4>
                            <ul class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                                @foreach($skill->cards as $card)
                                    <li>
                                        {{ $card->name }}
                                        ({{ $card->pivot->number }})
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($skill->unlocks->count() > 0)
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Unlocks') }}</h4>
                            <ul class="list-inside list-disc">
                                @foreach($skill->unlocks as $unlock)
                                    <li>{{ $unlock->skill->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($skill->discounts->count() > 0)
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mt-2">{{ __('Discounts') }}</h4>
                            <ul class="list-inside list-disc">
                                @foreach($skill->discounts as $discount)
                                    <li>{{ sprintf(__('%s (-%d months)'), $discount->discountedSkill->name, $discount->discount) }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
            <script src="{{ asset('js/characters.js') }}" defer></script>
        </div>
    </form>
</div>
