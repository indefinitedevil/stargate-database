<x-mail::message>
Hello {{ $character->user->name }},

The downtime has been processed, and here are your results:

<ul>
@foreach ($results as $result)
    <li>
        {{ $result['notes'] }}
        @if (!empty($result['amount_trained']))
            ({{ __('Trained :amount months', ['amount' => $result['amount_trained']]) }})
            @if (!empty($result['skill_completed']))
                ({{ __('Skill completed') }})
            @endif
        @endif
        @if (!empty($result['body_change']))
            ({{ __('Body :change', ['change' => add_positive_modifier($result['body_change'])]) }})
        @endif
        @if (!empty($result['vigor_change']))
            ({{ __('Vigor :change', ['change' => add_positive_modifier($result['vigor_change'])]) }})
        @endif
        @if (!empty($result['temp_body_change']))
            ({{ __('Body :change for next event', ['change' => add_positive_modifier($result['temp_body_change'])]) }})
        @endif
        @if (!empty($result['temp_vigor_change']))
            ({{ __('Vigor :change for next event', ['change' => add_positive_modifier($result['temp_vigor_change'])]) }})
        @endif
    </li>
@endforeach
</ul>

@if (!empty($researchResults))
    <h3>{{ __('Research Projects') }}</h3>
    <ul>
        @foreach ($researchResults as $projectResult)
            <li>
                <strong>{{ $projectResult['project']->name }}</strong>:
                {{ __('(:done / :total months)', ['done' => $projectResult['project']->researchActions()->count(), 'total' => $projectResult['project']->months]) }}
                @if (!empty($projectResult['contributors']))
                    <ul>
                        @foreach ($projectResult['contributors'] as $contributorId => $contributor)
                            <li>{{ __('Researcher: :name (:months months)', ['name' => current($contributor), 'months' => count($contributor)] }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (!empty($projectResult['volunteers']))
                    <ul>
                        @foreach ($projectResult['volunteers'] as $volunteer)
                            <li>{{ __('Volunteer: :name', ['name' => $volunteer]) }}</li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@endif

Regards,

Plot Coordinator
</x-mail::message>
