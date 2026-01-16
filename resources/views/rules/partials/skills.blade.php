@foreach ($categories as $category)
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow lg:rounded-lg text-gray-800 dark:text-gray-300">
        <h3 class="text-xl font-semibold">{!! trans_choice('{-1} :name Skills|[1,*] :name Skills (:cost months)', $category->cost, ['name' => $category->name, 'cost' => $category->cost]) !!}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
            @foreach($category->skills->sortBy('name') as $skill)
                <div class="mt-1">
                    <h4 class="text-lg font-semibold">{{ $skill->name }}</h4>
                    <ul>
                        <li>
                            <strong>{{ __('Description') }}</strong>
                            <div id="skill-{{ $skill->id }}" class="text-sm pl-4 space-y-2 mt-1 mb-2">
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
                                        <li>
                                            {{ $feat->name }}
                                            @if ($feat->cost)
                                                {{ __('(:cost Vigor)', ['cost' => $feat->cost]) }}
                                            @endif
                                            @if ($feat->per_day)
                                                {{ __('(:count per day)', ['count' => $feat->per_day]) }}
                                            @endif
                                            @if ($feat->per_event)
                                                {{ __('(:count per event)', ['count' => $feat->per_event]) }}
                                            @endif
                                        </li>
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
                                        <li>{{ trans_choice($ability, 1, ['count' => 1]) }}</li>
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
