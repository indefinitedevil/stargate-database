@php use App\Models\Team; @endphp
<strong><a href="{{ $team->getViewRoute() }}"
           class="underline">{{ $team->name }}</a></strong>
@can('edit teams')
    <a class="underline ms-6" href="{{ route('teams.edit', $team) }}">
        <i class="fa-solid fa-pen-to-square"></i>
        {{ __('Edit') }}
    </a>
@endcan
<ul class="list-inside list-disc pl-4 mt-1">
    @if ($team->characters->isEmpty())
        <li>{{ __('No members found') }}</li>
    @else
        @foreach ($team->characters as $character)
            <li>
                {{ $character->listName }}
                @if (Team::LEAD == $character->pivot->position)
                    <span class="text-sm text-gray-400 dark:text-gray-500">({{ __('Team Lead') }})</span>
                @elseif (Team::SECOND == $character->pivot->position)
                    <span class="text-sm text-gray-400 dark:text-gray-500">({{ __('Team 2IC') }})</span>
                @endif
            </li>
        @endforeach
    @endif
</ul>
