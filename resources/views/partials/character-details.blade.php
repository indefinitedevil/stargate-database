<div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg text-gray-800 dark:text-gray-300">
    <div class="grid grid-cols-4">
        <p class="mt-1">
            <strong>{{ __('Name') }}:</strong> {{ $character->name }}
        </p>
        <p class="mt-1">
            <strong>{{ __('Rank') }}
                :</strong> {!! $character->rank ?: '<abbr title="To be determined">TBD</abbr>' !!} @if ($character->former_rank)
                ({{ $character->former_rank }})
            @endif
        </p>
        <p class="mt-1">
            <strong>{{ __('Body') }}:</strong> {{ $character->body }}
        </p>
        <p class="mt-1">
            <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }}
        </p>
        <p class="mt-1">
            <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
        </p>
        <p class="mt-1">
            <strong>{{ __('Status') }}:</strong> {{ $character->status->name }}
        </p>
    </div>
</div>