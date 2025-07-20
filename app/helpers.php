<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

function add_positive_modifier(int $int): string
{
    return $int > 0 ? "+$int" : "$int";
}

function process_markdown(string $text): Stringable
{
    return Str::of($text)->markdown()
        ->replace('<ul>', '<ul class="list-disc list-inside">')
        ->replace('<blockquote>', '<blockquote class="px-4 py-2 border-l-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-700 text-gray-900 dark:text-white">');
}

function process_inline_markdown(string $text): Stringable
{
    return process_markdown($text)->replace('<p>', '')
        ->replace('</p>', '');
}

function format_datetime($date, $format = 'j F Y H:i'): string
{
    if (!($date instanceof Carbon)) {
        $date = (new Carbon(strtotime($date)))->shiftTimezone('UTC');
    }
    return $date->timezone('Europe/London')->format($format);
}

function utc_datetime($date, $format = 'Y-m-d H:i:s'): string
{
    if (!($date instanceof Carbon)) {
        $date = (new Carbon(strtotime($date)))->shiftTimezone('Europe/London');
    }
    return $date->timezone('UTC')->format($format);
}
