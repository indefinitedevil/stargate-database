<ul class="list-disc list-inside space-y-1">
    @if (count($characters) == 0)
        <li>{{ __('No characters found') }}</li>
    @else
        @foreach ($characters as $character)
            <li>
                @if (!empty($checkbox))
                    <input type="checkbox" name="characters[]" value="{{ $character->id }}">
                @endif
                @if (auth()->user()->can('view all characters') && empty($hideUser))
                    <span class="text-sm text-gray-400 dark:text-gray-500">
                        <a class="underline" href="{{ $character->user->getViewRoute() }}">{{ $character->user->name }}</a>@if ($character->user->pronouns) ({{ $character->user->pronouns }})@endif:
                    </span>
                @endif
                <a class="underline" href="{{ $character->getViewRoute() }}">
                    {{ $character->listName }}</a>
                @if ($character->pronouns)({{ $character->pronouns }})@endif
                @if ($character->isPrimary && empty($hidePrimary))
                    <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i>
                @endif
                ({{ $character->background->name }})
                @if (empty($hideStatus))
                    - {{ $character->status->name }}
                @endif
                @if (request()->routeIs('characters.*'))
                    -
                    <a class="underline" href="{{ route('characters.print', $character) }}"><i class="fa-solid fa-print"
                                                                                               title="{{ __('Print') }}"></i></a>
                @endif
            </li>
        @endforeach
    @endif
</ul>
