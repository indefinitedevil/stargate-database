<?php

use Illuminate\Support\Str;

function add_positive_modifier(int $int): string {
    return $int > 0 ? "+$int" : "$int";
}

function process_markdown(string $text): string {
    return Str::of($text)->markdown()->replace('<ul>', '<ul class="list-disc list-inside">');
}
