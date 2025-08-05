@php
    use App\Helpers\CharacterHelper;
    use App\Models\Skill;
    use App\Models\SkillCategory;
@endphp
<x-app-layout>
    <x-slot name="title">{{ __('Skill check') }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Skill check') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:px-8 sm:py-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
        <p>
            <strong>{{ __('Lowest training months on an active character:') }}</strong> {{ CharacterHelper::getLowestTrainingMonths() }}
        </p>
        <p>
            <strong>{{ __('Lowest training months on an active character who has done a downtime:') }}</strong> {{ CharacterHelper::getLowestTrainingMonthsIncludingDowntime() }}
        </p>
        <p>
            <strong>{{ __('Highest training months on an active character:') }}</strong> {{ CharacterHelper::getHighestTrainingMonths() }}
        </p>
        <p>
            <strong>{{ __('Catchup XP:') }}</strong> {{ CharacterHelper::getCatchupXP() }}
        </p>
    </div>

    @foreach (SkillCategory::all() as $category)
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
            <h3 class="text-xl font-semibold">{!! trans_choice('{-1} :name Skills|[1,*] :name Skills (:cost months)', $category->cost, ['name' => $category->name, 'cost' => $category->cost]) !!}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                @foreach($category->skills->sortBy('name') as $skill)
                    <div class="mt-1">
                        @php
                            @endphp
                        <h4 class="text-lg font-semibold">{{ $skill->name }}</h4>
                        <ul>
                            <li onclick="toggleVisibility('skill-{{ $skill->id }}')">
                                {!! __('<strong class="cursor-pointer underline decoration-dashed underline-offset-4">Description</strong>') !!}
                                <div id="skill-{{ $skill->id }}" class="text-sm hidden pl-4 space-y-2 mt-1 mb-2">
                                    {!! process_markdown($skill->description) !!}
                                </div>
                            </li>
                            @if ($skill->cost)
                                <li>
                                    {!! __('<strong>Cost:</strong> :cost months', ['cost' => $skill->cost]) !!}
                                </li>
                            @endif
                            @if ($skill->feats->count() > 0)
                                <li>
                                    <strong>{{ __('Feats') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->feats as $feat)
                                            <li>{{ $feat->name }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($skill->cards->count() > 0)
                                <li>
                                    <strong>{{ __('Cards') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->cards as $card)
                                            <li>{{ $card->name }} ({{ $card->pivot->number }})</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if (count($skill->abilities()) > 0)
                                <li>
                                    <strong>{{ __('Abilities') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->abilities() as $ability)
                                            <li>{{ $ability }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($skill->prereqs->count() > 0)
                                <li>
                                    <strong>{{ __('Pre-requisites') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->prereqs as $prereq)
                                            <li>{{ $prereq->requiredSkill->name }} {{ count($skill->prereqs) > 1 ? __($prereq->always_required ? '(AND)' : '(OR)') : '' }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($skill->unlocks->count() > 0)
                                <li>
                                    <strong>{{ __('Unlocks') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->unlocks as $unlock)
                                            <li>{{ $unlock->skill->name }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($skill->locks->count() > 0)
                                <li>
                                    <strong>{{ __('Locks') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->locks as $lock)
                                            <li>{{ $lock->locksOut->name }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($skill->discounts->count() > 0)
                                <li>
                                    <strong>{{ __('Discounts') }}</strong>
                                    <ul class="list-inside list-disc">
                                        @foreach($skill->discounts as $discount)
                                            <li>{{ __(':name (-:cost months)', ['name' => $discount->discountedSkill->name, 'cost' => $discount->discount]) }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                            @if ($skill->repeatable)
                                <li>
                                    {!! __('<strong>Repeatable:</strong> :repeatable times', ['repeatable' => $skill->repeatable]) !!}
                                </li>
                            @endif
                            @if ($skill->body)
                                <li>
                                    {!! __('<strong>Body:</strong> +:body', ['body' => $skill->body]) !!}
                                </li>
                            @endif
                            @if ($skill->vigor)
                                <li>
                                    {!! __('<strong>Vigor:</strong> +:vigor', ['vigor' => $skill->vigor]) !!}
                                </li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <script src="{{ asset('js/characters.js') }}" defer></script>
</x-app-layout>
