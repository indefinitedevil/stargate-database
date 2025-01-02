@php
    use App\Models\Event;
@endphp
<div>
    <p>This is a temporary measure, but if you could indicate which events you intend to
        attend as this character, that would help event runners greatly.</p>
    <div class="grid grid-cols-4">
        @foreach(Event::all() as $event)
            <label>
                <input type="checkbox" id="event_{{ $event->id }}" name="events[]"
                       value="{{ $event->id }}"
                       @if($character->events->contains($event->id)) checked @endif />
                {{ $event->name}}
                ({{ $event->start_date->format('d/m/y') }} - {{ $event->end_date->format('d/m/y') }})
            </label>
        @endforeach
    </div>
</div>
