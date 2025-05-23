<x-mail::message>
Hello {{ $user->name }},

A downtime has been announced:

It will open at {{ format_datetime($downtime->start_time, 'd/m/Y H:i') }} and close at {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }}.

@if($downtime->event_id)
This downtime is associated with the event {{ $downtime->event->name }} and open to attendees of same.
@endif

<x-mail::button :url="route('downtimes.index')">Go to Downtime System</x-mail::button>

Regards,

Plot Coordinator
</x-mail::message>
