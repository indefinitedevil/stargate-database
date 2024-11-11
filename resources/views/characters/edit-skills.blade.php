@php
    use App\Models\Background;
    use App\Models\Feat;use App\Models\Status;
    $fieldClass = 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full';
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Edit character skills') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ sprintf(__('Edit character skills: %s'), $character->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="grid grid-cols-4">
                    <p class="mt-1">
                        <strong>{{ __('Name') }}:</strong> {{ $character->name }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Body') }}:</strong> {{ $character->body }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }}
                    </p>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="grid grid-cols-4 clear-both">
                    <div class="mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Background') }}</h3>
                        <ul>
                            @foreach ($character->background->skills as $skill)
                                <li>{{ $skill->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="mt-1">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Trained') }}</h3>
                        <ul>
                            @foreach ($character->trainedSkills->sortBy('name') as $characterSkill)
                                <li>{{ $characterSkill->name }}
                                    @if ($characterSkill->locked)
                                        <i class="fa-solid fa-lock" title="Expenditure is locked"></i>
                                    @elseif ($characterSkill->discount_used)
                                        <i class="fa-solid fa-user-lock"
                                           title="{{ sprintf('Discounting %s', $characterSkill->discountUsedBy->skill->name) }}"></i>
                                    @elseif ($characterSkill->required)
                                        <i class="fa-solid fa-user-lock"
                                           title="{{ sprintf('Required by %s', $characterSkill->requiredBy) }}"></i>
                                    @else
                                        <a href="{{ route('characters.edit-skill', ['characterId' => $character->id, 'skillId' => $characterSkill->id]) }}"><i class="fa-solid fa-pencil" title="Edit skill"></i></a>
                                        <i class="fa-solid fa-trash" title="Remove skill"></i>
                                    @endif
                                    @if($characterSkill->skill->specialties > 1)
                                        <ul class="list-disc list-inside">
                                            @foreach ($characterSkill->specialties as $specialty)
                                                <li>{{ $specialty->name }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($character->trainingSkills->count())
                        <div class="mt-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Training') }}</h3>
                            <ul>
                                @foreach ($character->trainingSkills->sortBy('name') as $characterSkill)
                                    <li>
                                        {{ $characterSkill->name }}
                                        ({{ $characterSkill->trained }}/{{ $characterSkill->cost }})
                                        <i class="fa-solid fa-pencil" title="Edit skill"></i>
                                        @if ($characterSkill->locked)
                                            <i class="fa-solid fa-lock" title="Expenditure is locked"></i>
                                        @else
                                            <i class="fa-solid fa-trash" title="Remove skill"></i>
                                        @endif
                                        @if($characterSkill->skill->specialties > 1)
                                            <ul>
                                                @foreach ($characterSkill->skillSpecialties as $specialty)
                                                    <li>{{ $specialty->name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store-skill') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (!empty($editSkill))
                            <input type="hidden" name="edit_skill" value="{{ $editSkill->id }}">
                        @endif
                        <input type="hidden" name="character_id" value="{{$character->id }}">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="skill">Skill</label>
                                <select id="skill" name="skill_id" class="{{ $fieldClass }}" required>
                                    @if (!empty($editSkill))
                                        <option value="{{ $editSkill->skill_id }}" selected="selected">
                                            {{ $editSkill->skill->name }}
                                        </option>
                                    @else
                                        <option value="">Select a skill</option>
                                    @endif
                                    @foreach($character->availableSkills as $skill)
                                        <option value="{{ $skill->id }}">
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($editSkill && $editSkill->skill->specialties > 0)
                                <div>
                                    <label for="specialty">Specialty</label>
                                    <select id="specialty" name="specialty_id" class="{{ $fieldClass }}"
                                            @if ($editSkill->skill->specialties > 1) multiple @endif>
                                    >
                                        @foreach($editSkill->skill->specialtyType->skillSpecialties as $specialty)
                                            <option value="{{ $specialty->id }}"
                                                    @if($editSkill->skillSpecialties->contains($specialty)) selected @endif
                                            >
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($editSkill && ($editSkill->discountAvailable || $editSkill->discountedBy->count()))
                                <div>
                                    <label for="discount">Discount</label>
                                    <select id="discount" name="discount_id" class="{{ $fieldClass }}" multiple>
                                        <option value="">No discount</option>
                                        @foreach($editSkill->discountedBy as $discount)
                                            <option value="{{ $discount->id }}" selected>
                                                {{ $discount->skill->name }}
                                            </option>
                                        @endforeach
                                        @foreach($editSkill->discountsAvailable as $discount)
                                            <option value="{{ $discount->id }}"
                                                    @if($editSkill->discountedBy->contains(['id' => $discount->id])) selected @endif
                                            >
                                                {{ $discount->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div>
                                <label for="completed">Completed</label>
                                <input type="checkbox" id="completed" name="completed" class=""
                                        @if (!empty($editSkill) && $editSkill->completed) checked="checked" @endif>
                            </div>

                            <div class="flex items-center gap-4">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
