@php
    use App\Models\Downtime;
    $downtime = Downtime::getOpenDowntime();
@endphp
@if ($downtime)
    <div class="bg-sky-100 border-l-4 border-sky-500 text-sky-900 p-5 shadow">
        <p>{!! __('There is an open downtime period. Please <a href=":link" class="underline">complete your actions</a> before the deadline.', ['link' => route('downtimes.index')]) !!}</p>
        <p>{{ __('The deadline for this downtime period is :date.', ['date' => format_datetime($downtime->end_time, 'j F Y H:i')]) }}</p>
    </div>
@endif
