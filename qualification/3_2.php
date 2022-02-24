<?php

/*
    Дан массив чисел array_start.
    Вы можете менять местами элементы массива.
    Ваша задача: получить array_goal.
    Необходимо определить минимально возможное количество перемещений элементов массива,
    которое требуется, чтобы получить нужный порядок в массиве.

    Ввод:
    * array_start - начальный массив, 1<length(array_start)<10
    * array_goal - конечный массив, length(array_start)=length(array_goal)

    Вывод:
    * Integer

    Пример:
    array_start = [3, 2, 1, 4]
    array_goal = [1, 2, 3, 4]
    get_result(array_start, array_goal) = 1
    Меняем 3 и 1 местами и получаем нужную последовательность в массиве.
 */

function getResult(array $arrayStart, array $arrayGoal): int
{
    $inPlaces = 0;
    foreach ($arrayStart as $key => $value) {
        if ($arrayGoal[$key] === $value) {
            $inPlaces++;
        }
    }

    return (count($arrayStart) - $inPlaces) / 2;
}

//echo getResult([3, 2, 1, 4], [1, 2, 3, 4]) . "\n"; // 1
//echo getResult([1, 2, 3, 4], [4, 3, 2, 1]) . "\n"; // 2
