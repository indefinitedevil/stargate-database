@can('viewAll', $character)
    <p class="mb-2">
        <strong>{{ __('Player') }}:</strong> <a href="{{ $character->user->getViewRoute() }}"
            class="underline">{{ $character->user->name }}</a>
    </p>
@endcan
@if ($character->short_name)
    <p class="mb-1">
        <strong>{{ __('Short Name') }}:</strong> {{ $character->short_name }}
    </p>
@endif
<div class="grid grid-cols-1 sm:grid-cols-4 gap-x-6 gap-y-1">
    <p class="mt-1">
        <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Status') }}:</strong> {{ $character->status->name }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Body') }}:</strong> {{ $character->body }} @if ($character->temp_body) {{ __('(+:temp for next event)', ['temp' => $character->temp_body]) }} @endif
    </p>
    <p class="mt-1">
        <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }} @if ($character->temp_vigor) {{ __('(+:temp for next event)', ['temp' => $character->temp_vigor]) }} @endif
    </p>
    <p class="mt-1">
        <strong>{{ __('Type') }}:</strong> {{ $character->type }}
    </p>
    <p class="mt-1">
        <strong>{{ __('Genetics') }}:</strong> {!! $character->genetics_indicator !!}
    </p>
    <p class="mt-1 col-span-2">
        <strong>{{ __('Rank') }}:</strong> {!! $character->rank ?: __('To be determined') !!}
        @if (empty($character->rank) && $character->former_rank)
            ({{ $character->former_rank }})
        @endif
    </p>
</div>
