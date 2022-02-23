<?php

/*
 * Забыл скопировать условие, но оно такое мутное, что даже воспроизводить его лень.
 */

function getResult(array $arr, int $w): bool
{
    $max = 1;
    $actions = 0;
    while (true) {
        if ($actions > $max) {
            break;
        }
        if (array_sum($arr) <= $w) {
            return true;
        }

        $max = max($arr);
        $maxKey = array_search($max, $arr);
        $arr[$maxKey] = floor($arr[$maxKey] / 2);
        $actions++;
    }

    return false;
}

//echo getResult([3, 2, 4, 5], 9);
//echo getResult([3, 2, 4, 5], 6);
