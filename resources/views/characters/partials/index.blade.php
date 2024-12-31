<ul class="list-disc list-inside">
    @if (count($characters) == 0)
        <li>{{ __('No characters found') }}</li>
    @else
        @foreach ($characters as $character)
            <li>
                <a class="underline" href="{{ route('characters.view', $character) }}">
                    {{ $character->name }}</a>
                ({{ $character->background->name }})
                @if(auth()->user()->can('view all characters') && empty($hideUser))
                    [<a class="underline" href="{{ route('profile.view', $character->user) }}">{{ $character->user->name }}</a>]
                @endif
                @if (empty($hideStatus))
                - {{ $character->status->name }}
                @endif
            </li>
        @endforeach
    @endif
</ul>
