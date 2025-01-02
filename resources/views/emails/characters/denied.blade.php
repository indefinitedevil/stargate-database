<x-mail::message>
Hello {{ $character->user->name }},

Your character "{{ $character->name }}" has been denied. You can contact the Plot Coordinator for more information.

@if($notes)
Notes:
{{ $notes }}
@endif

Regards,

Plot Coordinator
</x-mail::message>
