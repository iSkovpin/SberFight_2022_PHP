<?php

/*
    Вы играете в игру, где ваш персонаж прыгает по заборчикам.
    Значения в массиве означают, сколько заборчиков персонаж
    обязан перешагнуть, двигаясь вперед.
    Вы можете менять элементы в массиве местами.
    Чтобы выиграть, персонажу нужно дойти до финиша,
    в нашем случае - это добраться до последнего индекса массива.
    Выведите true, если победить в игре возможно,
    в противном случае - false.

    Ввод:
    * fences - массив значений длин прыжков.
    Герой начинает с нулевого индекса,
    1<length(fences)<=20,
    -10<=fences[i]<=15

    Вывод:
    Boolean - возможно ли победить

    Example:
    fences = [0, 2, 4, 1, 6, 2]
    get_result(fences) = True
    Один из возможных вариантов: [1, 4, 2, 0, 6, 2].
    Герой с 0-го индекса прыгнул на 1-ый,
    и сразу же смог прыгнуть на последний индекс
    массива - он победил
 */


function getResult(array $fences): bool
{
//    echo "fences: " . count($fences) . "\n";
    return move(-1, 0, count($fences) - 1, $fences, []);
}

function move(int $toIdx, int $curSum, int $targetSum, array $currentArray, array $way): bool
{
    if ($toIdx !== -1) {
        $jump = $currentArray[$toIdx];
        if ($jump === 0) {
            return false;
        }

        $curSum += $jump;
//        $way[] = $jump;
        unset($currentArray[$toIdx]);

        if ($curSum === $targetSum) {
//            print_r($way);
            return true;
        }

        if ($curSum > $targetSum || $curSum < 0) {
            return false;
        }
    }

    foreach ($currentArray as $idx => $num) {
        if ($num === null) {
            continue;
        }

        if (move($idx, $curSum, $targetSum, $currentArray, $way) === true) {
            return true;
        }
    }

    return false;
}

//echo getResult([0, 2, 4, 1, 6, 2]) . "\n"; // true
//echo getResult([-1, 20, 4, 3, 66, 0, 8, -22, 7, 4, 5, 7, 9]) . "\n"; // true
//echo getResult([5, 5]) . "\n"; // false
