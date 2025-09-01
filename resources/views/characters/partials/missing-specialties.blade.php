@if ($character->skillsWithMissingSpecialties->count())
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-5 shadow">
        <p class="font-bold">{{ __('Skills with missing specialties') }}</p>
        <p>{{ __('The following skills require specialties to be selected:') }}</p>
        @php $completed = false; @endphp
        <ul class="list-disc list-inside">
            @foreach($character->skillsWithMissingSpecialties as $skill)
                @php if ($skill->completed) $completed = true; @endphp
                <li>@if ($skill->completed)
                        @can ('edit all characters')
                            <a href="{{ route('characters.edit-skill', ['characterId' => $character, 'skillId' => $skill]) }}"
                               class="underline">
                                {{ $skill->name }}
                            </a>
                        @else
                            {{ $skill->name }}
                        @endcan
                        *
                    @else
                        <a href="{{ route('characters.edit-skill', ['characterId' => $character, 'skillId' => $skill]) }}"
                           class="underline">
                            {{ $skill->name }}
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
        @if ($completed)
            <p class="mt-2 italic">
                * {{ __('Skills marked with an asterisk require the plot coordinator to amend them.') }}</p>
        @endif
    </div>
@endif
