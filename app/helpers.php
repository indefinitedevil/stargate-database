<?php

use Carbon\Carbon;
use Illuminate\Support\Str;

function add_positive_modifier(int $int): string {
    return $int > 0 ? "+$int" : "$int";
}

function process_markdown(string $text): string {
    return Str::of($text)->markdown()->replace('<ul>', '<ul class="list-disc list-inside">');
}

function format_datetime($date, $format = 'j F Y H:i'): string {
    if (!($date instanceof Carbon)) {
        $date = (new Carbon(strtotime($date)))->shiftTimezone('UTC');
    }
    return $date->timezone('Europe/London')->format($format);
}

function utc_datetime($date, $format = 'Y-m-d H:i:s'): string {
    if (!($date instanceof Carbon)) {
        $date = (new Carbon(strtotime($date)))->shiftTimezone('Europe/London');
    }
    return $date->timezone('UTC')->format($format);
}
