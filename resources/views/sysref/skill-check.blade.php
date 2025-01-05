@php
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.errors')
            @foreach (SkillCategory::all() as $category)
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
                    <h3 class="text-xl font-semibold">{!! sprintf('%s Skills (%d months)', $category->name, $category->cost) !!}</h3>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($category->skills->sortBy('name') as $skill)
                            <div class="mt-1">
                                @php
                                    @endphp
                                <h4 class="text-lg font-semibold">{{ $skill->name }}</h4>
                                <ul>
                                    @if ($skill->cost)
                                        <li>
                                            {!! sprintf(__('<strong>Cost:</strong> %d months'), $skill->cost) !!}
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
                                    @if ($skill->prereqs->count() > 0)
                                        <li>
                                            <strong>{{ __('Pre-requisites') }}</strong>
                                            <ul class="list-inside list-disc">
                                                @foreach($skill->prereqs as $prereq)
                                                    <li>{{ $prereq->requiredSkill->name }}</li>
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
                                                    <li>{{ sprintf(__('%s (-%d months)'), $discount->discountedSkill->name, $discount->discount) }}</li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                    @if ($skill->repeatable)
                                        <li>
                                            {!! sprintf(__('<strong>Repeatable:</strong> %s times'), $skill->repeatable) !!}
                                        </li>
                                    @endif
                                    @if ($skill->body)
                                        <li>
                                            {!! sprintf(__('<strong>Body:</strong> +%s'), $skill->body) !!}
                                        </li>
                                    @endif
                                    @if ($skill->vigor)
                                        <li>
                                            {!! sprintf(__('<strong>Vigor:</strong> +%s'), $skill->vigor) !!}
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
