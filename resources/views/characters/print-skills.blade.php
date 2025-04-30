@php
    use App\Models\Feat;
    use App\Models\Status;
@endphp
<x-print-layout>
    <x-slot name="title">{{ __('Character skill printing') }}</x-slot>

    @foreach($characters as $character)
        <div class="break-after-page">
            <div class="max-w-7xl mx-auto space-y-2">
                <div class="py-2 bg-white text-gray-800">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900">
                            {{ __('Skills') }}
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 clear-both">
                            @if ($character->status_id < Status::APPROVED)
                                <div>
                                    <ul>
                                        @foreach ($character->background->skills as $skill)
                                            <li>
                                                {{ $skill->print_name ?? $skill->name }}
                                                <div class="text-sm pl-4 space-y-2">
                                                    {!! process_markdown($skill->description) !!}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @foreach($character->displayedTrainedSkills->chunk(4) as $trainedSkills)
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
                                                <div class="text-sm pl-4 space-y-2">
                                                    {!! process_markdown($characterSkill->skill->description) !!}
                                                </div>
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
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 mt-1">
                            @foreach ($character->feats as $feat)
                                <li>
                                    {{ $feat->name }}
                                    @if ($feat->per_event)
                                        ({{ $feat->getPerEvent($character) }})
                                    @endif
                                    <div class="text-sm pl-4 space-y-2">
                                        {!! process_markdown($feat->description) !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-print-layout>
