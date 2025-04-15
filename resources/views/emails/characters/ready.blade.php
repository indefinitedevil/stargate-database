<x-mail::message>
Hello Plot Coordinator,

{{ $character->user->name }} has submitted the character "{{ $character->name }}" for approval.

<x-mail::button :url="$character->getViewRoute()">Go to Downtime System</x-mail::button>

Regards,

Character Database
</x-mail::message>
