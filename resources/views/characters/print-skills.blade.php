@php
    use App\Models\Feat;
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
                        <div class="grid grid-cols-2 gap-x-4 clear-both">
                            <div class="mt-1">
                                <ul>
                                    @foreach ($character->background->skills as $skill)
                                        <li>
                                            {{ $skill->print_name ?? $skill->name }}
                                            <div class="text-sm pl-4 space-y-2">
                                                {!! Str::of($skill->description)->markdown()->replace('<ul>', '<ul class="list-disc list-inside">') !!}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @foreach($character->displayedTrainedSkills->chunk(4) as $trainedSkills)
                                <div class="mt-1">
                                    <ul>
                                        @foreach ($trainedSkills as $characterSkill)
                                            <li>{{ $characterSkill->print_name }}
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
                                                <div class="text-sm pl-4 space-y-2">
                                                    {!! Str::of($characterSkill->skill->description)->markdown()->replace('<ul>', '<ul class="list-disc list-inside">') !!}
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                        @if(!empty($flashOfInsight))
                            <p class="mt-1 text-sm">{{ __('* Flash of Insight discount available') }}</p>
                        @endif
                    </div>
                </div>

                <div class="py-2 bg-white text-gray-800">
                    <div class="">
                        <h2 class="text-xl font-medium text-gray-900">
                            {{ __('Feats') }}
                        </h2>
                        <ul class="grid grid-cols-2 gap-x-4 mt-1">
                            @foreach ($character->feats as $feat)
                                <li>
                                    {{ $feat->name }}
                                    @if ($feat->per_event)
                                        ({{ $feat->getPerEvent($character) }})
                                    @endif
                                    <div class="text-sm pl-4 space-y-2">
                                        {!! Str::of($feat->description)->markdown() !!}
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