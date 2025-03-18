@php
    use App\Models\Downtime;
    $downtime = Downtime::getOpenDowntime();
@endphp
@if ($downtime)
    <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-5 shadow">
        <p>{!! __('There is an open downtime period. Please <a href=":link" class="underline">complete your actions</a> before the deadline.', ['link' => route('downtimes.index')]) !!}</p>
        <p>{{ __('The deadline for this downtime period is :date.', ['date' => $downtime->end_time->format('j F Y H:i')]) }}</p>
    </div>
@endif
