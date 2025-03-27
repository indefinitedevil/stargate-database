<x-mail::message>
Hello {{ $character->user->name }},

The downtime has been processed, and here are your results:

<ul>
@foreach ($results as $result)
    <li>
        {{ $result['notes'] }}
        @if (!empty($result['amount_trained']))
            ({{ __('Trained :amount months', ['amount' => $result['amount_trained']]) }})
        @endif
        @if (!empty($result['vigor_change']))
            ({{ __('Vigor +:change for next event', ['change' => $result['vigor_change']]) }})
        @endif
    </li>
@endforeach
</ul>

Regards,

Plot Coordinator
</x-mail::message>
