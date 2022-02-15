<?php

function getResult(array $numb): bool
{
    $values = array_count_values($numb);
    $vvalues = array_count_values($values);

    if (isset($vvalues[1]) && $vvalues[1] > 1) {
        return false;
    }

    foreach ($values as $val => $num) {
        if ($num !== 1 && $num % 2 !== 0) {
            return false;
        }
    }

    return true;
}
