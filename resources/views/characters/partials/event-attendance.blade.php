@php
    use App\Models\Event;
@endphp
<div>
    <p>This is a temporary measure, but if you could indicate which events you intend to
        attend as this character, that would help the event runners and plot cos greatly.</p>
    <div class="grid grid-cols-4 mt-1">
        @foreach(Event::all() as $event)
            <x-input-label>
                <input type="checkbox" id="event_{{ $event->id }}" name="events[]"
                       value="{{ $event->id }}"
                       @if(!empty($character) && $character->events->contains($event->id)) checked @endif />
                {{ $event->name}}
                ({{ $event->start_date->format('d/m/y') }} - {{ $event->end_date->format('d/m/y') }})
            </x-input-label>
        @endforeach
    </div>
</div>
