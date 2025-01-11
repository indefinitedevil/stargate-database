<x-mail::message>
Hello Plot Coordinator,

{{ $character->user->name }} has submitted the character "{{ $character->name }}" for approval.

Link: [{{ route('characters.view', $character) }}]({{ route('characters.view', $character) }})

Regards,

Character Database
</x-mail::message>
