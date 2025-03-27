<x-mail::message>
Hello {{ $user->name }},

A downtime has been announced:

It will open at {{ $downtime->start_time->format('d/m/Y H:i') }} and close at {{ $downtime->end_time->format('d/m/Y H:i') }}.

@if($downtime->event_id)
This downtime is associated with the event {{ $downtime->event->name }} and open to attendees of same.
@endif

Regards,

Plot Coordinator
</x-mail::message>
