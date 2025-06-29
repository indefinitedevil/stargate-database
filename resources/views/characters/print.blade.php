@php
    use App\Models\Feat;
    use App\Models\Status;
@endphp
<x-print-layout>
    <x-slot name="title">{{ __('Character printing') }}</x-slot>

    @foreach($characters as $character)
        <div class="break-after-page">
            <div class="max-w-7xl mx-auto">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $character->listName }}
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
                            <strong>{{ __('Body') }}:</strong> {{ $character->body }} @if ($character->temp_body) {{ __('(+:temp for this event)', ['temp' => $character->temp_body]) }} @endif
                        </p>
                        <p class="mt-1 sm:col-span-2">
                            <strong>{{ __('Rank') }}:</strong> {!! $character->rank ?: __('To Be Determined') !!}
                            @if (empty($character->rank) && $character->former_rank)
                                ({{ $character->former_rank }})
                            @endif
                        </p>
                        <p class="mt-1">
                            <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }} @if ($character->temp_vigor) {{ __('(+:temp for this event)', ['temp' => $character->temp_vigor]) }} @endif
                        </p>
                    </div>
                </div>

                <div class="py-2 bg-white text-gray-800">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900">
                            {{ __('Skills') }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-4 clear-both">
                            @if ($character->status_id < Status::APPROVED)
                                <div>
                                    <ul>
                                        @foreach ($character->background->skills as $skill)
                                            <li>{{ $skill->print_name ?? $skill->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @php $botchJob = false; @endphp
                            @foreach($character->displayedTrainedSkills->chunk(5) as $trainedSkills)
                                <div>
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
                        @if (!empty($flashOfInsight))
                            <p class="mt-1 text-sm">{{ __('* Flash of Insight discount available') }}</p>
                        @endif
                        @if (!empty($botchJob))
                            <p class="mt-1 text-sm">{{ __('† Botch Job available') }}</p>
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
                                    {{ $feat->print_name ?: $feat->name }}
                                    {{ '' != $feat->cost ? '(' . $feat->cost . ' Vigor)' : '' }}
                                    @if ($feat->per_event)
                                        ({{ __(':count per event', ['count' => $feat->getPerEvent($character)]) }})
                                    @endif
                                    @if ($feat->per_day)
                                        ({{ __(':count per day', ['count' => $feat->getPerDay($character)]) }})
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

                @if (!empty($character->other_abilities))
                    <div class="py-2 bg-white text-gray-800">
                        <div class="">
                            <h2 class="text-xl font-medium text-gray-900">
                                {{ __('Other Abilities') }}
                            </h2>
                            <div class="mt-1">{!! process_markdown($character->other_abilities) !!}</div>
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
                                    <td class="border border-slate-600 px-2">Add One Potential Complication<sup>1</sup>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication<sup>1</sup>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2"><em>You ain't got time to bleed</em> – You
                                        Gain 1 Maximum Vigor for this event
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Raise the difficulty<sup>2</sup> of any
                                        Surgical Procedure by 1
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication<sup>1</sup>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">All Med Cards take 2x the printed time on
                                        you
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add One Potential Complication<sup>1</sup>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Raise the difficulty of any Surgical
                                        Procedure by 1
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Add one Extra Complication<sup>3</sup></td>
                                </tr>
                                <tr>
                                    <td class="border border-slate-600"></td>
                                    <td class="border border-slate-600 px-2">Any Failed Surgery Attempt will result you
                                        becoming Terminal
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="text-xs space-y-2">
                                <p><sup>1</sup> <strong>Potential complication:</strong> A complication card is shuffled
                                    into the Problem cards, then the normal number of cards for that wound is played.
                                </p>
                                <p><sup>2</sup> <strong>Raise the difficulty:</strong> The normal number of cards played
                                    for surgical procedure is increased by one. This new card will now definitely be one
                                    of the Complication Cards added by potential complication.</p>
                                <p><sup>3</sup> <strong>Add one extra Complication:</strong> Directly add one
                                    complication card to the SP cards after all other modifiers.</p>
                                <p>After any successful surgery, your Body is reset to 1 regardless of what it was
                                    before surgery.</p>
                                <p>If you are on 0 Body and a failed surgery would deal damage, you become Terminal.</p>
                            </div>
                        </div>
                        <div class="mt-4 space-y-2 text-sm">
                            <p>
                                Open a <strong>black wound card</strong> when you fall to 3 Body or below.
                                Do not open another black wound card until after your hits have been restored above 3.
                            </p>
                            <p>Open a <strong>white wound card</strong> when you fall to 0 Body.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                                <div>
                                    <p>While on 0 Body (Critical), you are:</p>
                                    <ul class="list-inside list-disc">
                                        <li>Bleeding (you will be Terminal in three minutes) unless treated</li>
                                        <li>Unable to stand or walk unassisted (crawling slowly is your limit)</li>
                                        <li>Unable to use any skills or abilities (including Medic)</li>
                                        <li>Unable to partake in combat</li>
                                        <li>Counted as Unresisting</li>
                                        <li>Conscious unless a wound card or other effect says otherwise</li>
                                        <li>Able to shout or use a radio to call for help</li>
                                        <li>Able to use feats (such as Cauterize to pause your bleed count)</li>
                                    </ul>
                                </div>
                                <div>
                                    <p>How bleeding works:</p>
                                    <ul class="list-inside list-disc">
                                        <li>If you are above 0 Body, treating a Bleed effect resets your bleed count to
                                            three minutes
                                        </li>
                                        <li>If you are on 0 Body, treating your bleeding pauses your bleed count</li>
                                        <li>Any significant movement while on 0 Body restarts your bleed count</li>
                                        <li>Use of "On Your Feet, Soldier" while on 0 Body allows you to move with that
                                            character's assistance and pauses your bleed count for the duration
                                        </li>
                                        <li>Use of "Prep For Movement" while stabilised resets your bleed count to three
                                            minutes
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h2 class="text-xl font-medium text-gray-900">
                                {{ __('Notes') }}
                            </h2>
                            <p class="text-sm">{{ __('Leave notes on drug usage, resuscitation, or anything else the plot co might need to know about.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-print-layout>
