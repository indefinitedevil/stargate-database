@if ($downtime->getResearchProjectsForCharacter($character->id)->count() > 0)
    <option value="">{{ __('Select a research project') }}</option>
    @foreach ($downtime->getResearchProjectsForCharacter($character->id) as $project)
        <option value="{{$project->id}}"
                @if (!empty($action->research_project_id) && $project->id == $action->research_project_id) selected @endif
        >
            {{ $project->name }}
        </option>
    @endforeach
@else
    <option value="" disabled>{{ __('No research projects are available') }}</option>
@endif
