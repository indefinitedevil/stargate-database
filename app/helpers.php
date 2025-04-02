<?php

function add_positive_modifier(int $int): string {
    return $int > 0 ? "+$int" : "$int";
}
