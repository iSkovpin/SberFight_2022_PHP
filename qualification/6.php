<?php

/*
    Поиск сокровищ.
    В день можно ходить / отдохнуть.
    Если идешь 2й день подряд - получаешь вдвое меньше награды за ход, а после этого обязательно нужно отдохнуть (? вроде).
    Отдохнуть можно в любой ход.
    Отдохнуть 2 дня подряд нельзя (?) - в любом случае скорее всего это не выгодно.
    Необходимо максимизировать сумму сокровищ.
 */

function getResult(array $treasures): int
{
    return ceil(calcMaxTreasures($treasures, 0, -1, false, [null, null]));
}

function calcMaxTreasures(array $treasures, float $sum, int $index, bool $turnValue, array $prevTurns): float
{
//    echo sprintf("sum: %s, index: %s, val: %s, prev: %s, %s", $sum, $index, (int)$turnValue, (int)$prevTurns[0], (int)$prevTurns[1]) . "\n";

    if ($index !== -1) { // initial value
        if ($prevTurns[0] === false && $prevTurns[1] === false) {
            return $sum;
        }

        if ($turnValue === true && $prevTurns[1] === false) {
            $sum += $treasures[$index];
        } elseif ($turnValue === true && $prevTurns[1] === true) {
            $sum += $treasures[$index] / 2;
        }

        if ($index === count($treasures) - 1) {
            return $sum;
        }
    }

    $subTreeSum1 = calcMaxTreasures($treasures, $sum, $index + 1, true, [$prevTurns[1], $turnValue]);
    $subTreeSum2 = calcMaxTreasures($treasures, $sum, $index + 1, false, [$prevTurns[1], $turnValue]);

    return max($subTreeSum1, $subTreeSum2);
}

//echo getResult([4, 2, 5, 1, 5]) . "\n"; //14
//echo getResult([3, 1, 10, 6, 3, 10]) . "\n"; //26
//echo getResult([5, 4, 10, 2, 5]) . "\n"; //20
