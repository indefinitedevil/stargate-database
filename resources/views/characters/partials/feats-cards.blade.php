<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
    <div class="">
        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
            {{ __('Feats') }}
        </h2>
        <ul class="grid grid-cols-1 sm:grid-cols-4 gap-x-6 mt-1 gap-y-6 sm:gap-y-2">
            @foreach ($character->feats as $feat)
                <li>
                    <span class="cursor-pointer underline decoration-dashed underline-offset-4"
                          onclick="toggleVisibility('feat-{{ $feat->id }}')">
                        {{ $feat->print_name ?: $feat->name }}
                        {{ '' != $feat->cost ? '(' . $feat->cost . ' Vigor)' : '' }}
                        @if ($feat->per_event)
                            ({{ __(':count per event', ['count' => $feat->getPerEvent($character)]) }})
                        @endif
                        @if ($feat->per_day)
                            ({{ __(':count per day', ['count' => $feat->getPerDay($character)]) }})
                        @endif
                        <i class="fa-regular fa-circle-question" title="{{ __('Show description') }}"
                           data-tooltip-target="feat-{{ $feat->id }}"
                           onclick="toggleVisibility('feat-{{ $feat->id }}')"
                        ></i>
                    </span>
                    <div id="feat-{{ $feat->id }}" class="text-sm hidden pl-4">
                        {!! process_markdown($feat->description) !!}
                    </div>
                </li>
            @endforeach
        </ul>

        @if (!empty($character->cards))
            <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100 mt-4">
                {{ __('Cards') }}
            </h2>
            <ul class="grid grid-cols-1 sm:grid-cols-4 mt-1 gap-x-6 gap-y-2">
                @foreach ($character->cards as $card)
                    <li>{{ $card->name }} ({{ $card->number }})</li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
