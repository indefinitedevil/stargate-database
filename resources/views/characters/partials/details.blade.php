<div class="grid grid-cols-1 sm:grid-cols-4">
    <p class="mt-1">
        <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Status') }}:</strong> {{ $character->status->name }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Body') }}:</strong> {{ $character->body }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Type') }}:</strong> {{ $character->type }}
    </p>
    <p class="mt-1 col-span-2">
        <strong>{{ __('Rank') }}
            :</strong> {!! $character->rank ?: __('To be determined') !!} @if ($character->former_rank)
            ({{ $character->former_rank }})
        @endif
    </p>
</div>
