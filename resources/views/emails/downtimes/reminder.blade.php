<x-mail::message>
Hello {{ $user->name }},

The current downtime period will close at {{ format_datetime($downtime->end_time, 'd/m/Y H:i') }}. Please complete your actions before the deadline.

@if ($downtime->event_id)
This downtime is associated with the event {{ $downtime->event->name }} and open to attendees of same.
@endif

<x-mail::button :url="route('downtimes.index')">Go to Downtime System</x-mail::button>

Regards,

Plot Coordinator
</x-mail::message>
