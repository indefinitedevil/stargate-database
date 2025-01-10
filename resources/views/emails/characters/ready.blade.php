<x-mail::message>
Hello Plot Coordinator,

The character "{{ $character->name }}" has been marked as ready for approval.

Link: [{{ route('characters.view', $character) }}]({{ route('characters.view', $character) }})

Regards,

Character Database
</x-mail::message>
