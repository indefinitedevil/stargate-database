<ul class="list-disc list-inside space-y-1">
    @if (count($characters) == 0)
        <li>{{ __('No characters found') }}</li>
    @else
        @foreach ($characters as $character)
            <li>
                @if (!empty($checkbox))
                    <input type="checkbox" name="characters[]" value="{{ $character->id }}">
                @endif
                <a class="underline" href="{{ $character->getViewRoute() }}">
                    {{ $character->short_name ?: $character->name }}</a>
                @if($character->isPrimary)
                    <i class="fa-solid fa-star" title="{{ __('Primary character') }}"></i>
                @endif
                               ({{ $character->background->name }})
                @if(auth()->user()->can('view all characters') && empty($hideUser))
                    [<a class="underline" href="{{ $character->user->getViewRoute() }}">{{ $character->user->name }}</a>]
                @endif
                @if (empty($hideStatus))
                    - {{ $character->status->name }}
                @endif
                -
                <a class="underline" href="{{ route('characters.print', $character) }}"><i class="fa-solid fa-print"
                                                                                           title="{{ __('Print') }}"></i></a>
            </li>
        @endforeach
    @endif
</ul>
