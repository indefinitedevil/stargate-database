@php
    use App\Models\Feat;
@endphp
<x-print-layout>
    <x-slot name="title">{{ $character->name }}</x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight sm:px-6 lg:px-8">
            {{ sprintf(__('Character: %s'), $character->name) }}
        </h2>
    </x-slot>

    <div class="">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-2">
            <div class="px-8 bg-white text-gray-800 mb-2">
                <div class="grid grid-cols-3 gap-x-4">
                    <p class="mt-1 col-span-2">
                        <strong>{{ __('Background') }}:</strong> {{ $character->background->name }}
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Body') }}:</strong> {{ $character->body }}
                    </p>
                    <p class="mt-1 col-span-2">
                        <strong>{{ __('Rank') }}
                            :</strong> {!! $character->rank ?: '<abbr title="To be determined">TBD</abbr>' !!} @if ($character->former_rank)
                            ({{ $character->former_rank }})
                        @endif
                    </p>
                    <p class="mt-1">
                        <strong>{{ __('Vigor') }}:</strong> {{ $character->vigor }}
                    </p>
                </div>
            </div>

            <div class="px-8 py-2 bg-white text-gray-800">
                <div class="">
                    <h2 class="text-xl font-medium text-gray-900">
                        {{ __('Skills') }}
                    </h2>
                    <div class="grid grid-cols-3 gap-x-4 clear-both">
                        <div class="mt-1">
                            <ul>
                                @foreach ($character->background->skills as $skill)
                                    <li>{{ $skill->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @foreach($character->displayedTrainedSkills->chunk(5) as $trainedSkills)
                            <div class="mt-1">
                                <ul>
                                    @foreach ($trainedSkills as $characterSkill)
                                        <li>{{ $characterSkill->name }}
                                            @if($characterSkill->skill->feats->contains(Feat::FLASH_OF_INSIGHT))
                                                *
                                                @php $flashOfInsight = true; @endphp
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
                        <p class="mt-1">{{ __('* Flash of Insight discount available') }}</p>
                    @endif
                </div>
            </div>

            <div class="px-8 py-2 bg-white text-gray-800">
                <div class="">
                    <h2 class="text-xl font-medium text-gray-900">
                        {{ __('Feats') }}
                    </h2>
                    <ul class="grid grid-cols-3 gap-x-4 mt-1">
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
                <div class="px-8 py-2 bg-white text-gray-800">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900">
                            {{ __('Cards') }}
                        </h2>
                        <ul class="grid grid-cols-4 gap-x-4 mt-1">
                            @foreach ($character->cards as $card)
                                <li>{{ $card->name }} ({{ $card->number }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="px-8 py-2 bg-white text-gray-800 mt-2">
                <div class="">
                    <div class="grid grid-cols-2 gap-x-4">
                        <table class="table-auto border border-collapse border-slate-600 w-full">
                            <thead>
                            <tr>
                                <th class="border border-slate-600">Surgery</th>
                                <th class="border border-slate-600">Consequence</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Something happens</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Complication</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Extra Vigor</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Something happens</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Complication</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Extra Vigor</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Something happens</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Complication</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Extra Vigor</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Something happens</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Complication</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-600"></td>
                                <td class="border border-slate-600 px-2">Extra Vigor</td>
                            </tr>
                            </tbody>
                        </table>
                        <div>
                            <h2 class="text-xl font-medium text-gray-900">
                                {{ __('Notes') }}
                            </h2>
                            <p class="text-sm">Leave any notes on drug usage or anything else the plot co might need to
                                know.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-print-layout>