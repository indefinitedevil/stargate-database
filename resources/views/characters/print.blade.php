@php
    use App\Models\Feat;
@endphp
<x-print-layout>
    <x-slot name="title">{{ __('Character printing') }}</x-slot>

    @foreach($characters as $character)
        <div class="break-after-page">
            <div class="max-w-7xl mx-auto">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $character->short_name ?: $character->name }}
                    <span class="text-sm ml-4">
                        ({{ $character->user->name }})
                    </span>
                </h2>
            </div>
            <div class="max-w-7xl mx-auto space-y-2">
                <div class="bg-white text-gray-800 mb-2">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4">
                        <p class="mt-1">
                            <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
                        </p>
                        <p class="mt-1">
                            <strong>{{ __('Type') }}:</strong> {{ $character->type }}
                        </p>
                        <p class="mt-1">
                            <strong>{{ __('Body') }}:</strong> {{ $character->body }}
                        </p>
                        <p class="mt-1 sm:col-span-2">
                            <strong>{{ __('Rank') }}:</strong> {!! $character->rank ?: __('To Be Determined') !!}
                            @if (empty($character->rank) && $character->former_rank)
                                ({{ $character->former_rank }})
                            @endif
                        </p>
                        <p class="mt-1">
                            <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }}
                        </p>
                    </div>
                </div>

                <div class="py-2 bg-white text-gray-800">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900">
                            {{ __('Skills') }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 clear-both">
                            <div class="mt-1">
                                <ul>
                                    @foreach ($character->background->skills as $skill)
                                        <li>{{ $skill->print_name ?? $skill->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @foreach($character->displayedTrainedSkills->chunk(5) as $trainedSkills)
                                <div class="mt-1">
                                    <ul>
                                        @foreach ($trainedSkills as $characterSkill)
                                            <li>{{ $characterSkill->print_name }}
                                                @if($characterSkill->skill->feats->contains(Feat::FLASH_OF_INSIGHT))
                                                    *
                                                    @php $flashOfInsight = true; @endphp
                                                @endif
                                                @if($characterSkill->skill->feats->contains(Feat::BOTCH_JOB))
                                                    †
                                                    @php $botchJob = true; @endphp
                                                @endif
                                                @if($characterSkill->skill->specialties > 1)
                                                    <ul class="list-disc list-inside">
                                                        @foreach ($characterSkill->allSpecialties as $specialty)
                                                            <li>{{ $specialty->name }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                        @if(!empty($flashOfInsight))
                            <p class="mt-1 text-sm">{{ __('* Flash of Insight discount available') }}</p>
                        @endif
                        @if(!empty($botchJob))
                            <p class="mt-1">{{ __('† Botch Job available') }}</p>
                        @endif
                    </div>
                </div>

                <div class="py-2 bg-white text-gray-800">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900">
                            {{ __('Feats') }}
                        </h2>
                        <ul class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 mt-1">
                            @foreach ($character->feats as $feat)
                                <li>
                                    {{ $feat->name }}
                                    @if ($feat->per_event)
                                        ({{ $feat->getPerEvent($character) }})
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                @if (!empty($character->cards))
                    <div class="py-2 bg-white text-gray-800">
                        <div class="">
                            <h2 class="text-xl font-medium text-gray-900">
                                {{ __('Cards') }}
                            </h2>
                            <ul class="grid grid-cols-2 sm:grid-cols-4 gap-x-4 mt-1">
                                @foreach ($character->cards as $card)
                                    <li>{{ $card->name }} ({{ $card->number }})</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <div class="py-2 bg-white text-gray-800 mt-2 break-before-page">
                    <div class="">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4">
                            <table class="table-auto border border-collapse border-slate-600 w-full sm:col-span-2">
                                <thead>
                                <tr>
                                    <th class="border border-slate-600">Surgery</th>
                                    <th class="border border-slate-600">Consequence</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication¹</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication¹</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2"><em>You ain’t got time to bleed</em> – You
                                        Gain 2 Vitality
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Raise the difficulty² of any Surgical
                                        Procedure by 1
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication¹</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">All Med Cards take 2x the printed time on
                                        you
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2"><em>It’s only a Flesh Wound</em> – Gain
                                        Feat Mastery Flesh Wounds until the end of the event
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication¹</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Raise the difficulty of any Surgical
                                        Procedure by 1
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add one Extra complication³</td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Any Failed Surgery Attempt is now fatal
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div>
                                {{-- footnotes left --}}
                                <p class="text-xs">¹ <strong>Potential complication:</strong> A complication card is
                                    shuffled into the Problem cards, then the normal number of cards for that wound is
                                    played.</p>
                                <p class="text-xs">² <strong>Raise the difficulty:</strong> The normal number of cards
                                    played for surgical procedure is increased by one. This new card will now definitely
                                    be one of the Complication Cards added by potential complication.</p>
                                <p class="text-xs">³ <strong>Add one extra Complication:</strong> Directly add one
                                    complication card to the SP cards after all other modifiers.</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h2 class="text-xl font-medium text-gray-900">
                                {{ __('Notes') }}
                            </h2>
                            <p class="text-sm">{{ __('Leave notes on drug usage or anything else the plot co might need to know about.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-print-layout>
