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
                                        <i class="fa-solid fa-pencil" title="Edit skill"></i>
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
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                <div class="mt-1">
                    <form method="POST" action="{{ route('characters.store-skills') }}">
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
                        <input type="hidden" name="character_id" value="{{$character->id }}">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="skill">Skill</label>
                                <select id="skill" name="skill_id" class="{{ $fieldClass }}" required>
                                    @foreach($character->availableSkills as $skill)
                                        <option value="{{ $skill->id }}">
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>
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