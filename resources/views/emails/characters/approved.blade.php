<x-mail::message>
Hello {{ $character->user->name }},

Your character "{{ $character->name }}" has been approved and can now be played at Stargate events.

@if($notes)
Notes:
{{ $notes }}
@endif

Regards,

Plot Coordinator
</x-mail::message>
