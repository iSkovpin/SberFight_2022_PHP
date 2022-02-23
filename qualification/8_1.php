<?php

/*
    Илон Маск презентовал свой новый космический проект - он запустил в небо большое количество ракет. Дан массив rocket_pos, где rocket_pos[i] - высота, на которой находится i-я ракета и rocket_speed, где rocket_speed[i] - скорость i-й ракеты (величина перемещения за одну единицу времени).

    Если ракеты достигают одинаковой высоты на каком-либо шаге, то они складываются в одну ракету, их скорость тоже складывается.

    Определите, сколько ракет будет в итоге.

    Ввод:

        rocket_pos - начальные позиции каждой из ракет (Integer[]), 0<length(rocket_pos)<=10, 0<=rocket_pos[i]<=1000
        rocket_speed - скорость каждой из ракет (Integer[]), 0<length(rocket_speed)<=10, 0<=rocket_speed[i]<=15

    Вывод:

        Integer - количество ракет по итогу всех "соединений"

    Пример 1:

    rocket_pos = [3, 11]
    rocket_speed = [5, 1]
    getResult(rocket_pos, rocket_speed) = 1 // Уже через две единицы времени обе ракеты будут на высоте 13 и соединятся

    Пример 2:

    rocket_pos = [2, 3]
    rocket_speed = [1, 2]
    getResult(rocket_pos, rocket_speed) = 2 // Ракеты никогда не соединятся
 */

function getResult(array $rocketPos, array $rocketSpeed): int
{
    $prevPositions = array_merge($rocketPos, [1]);

    while (isMergePossible($prevPositions, $rocketPos)) {
        $prevPositions = $rocketPos;
        $cv = array_filter(array_count_values($rocketPos), function ($freq) {
            return $freq > 1;
        });

        foreach (array_keys($cv) as $value) {
            $speed = 0;
            $rockets = count($rocketPos);
            for ($i = 0; $i < $rockets; $i++) {
                if ($rocketPos[$i] !== $value) {
                    continue;
                }

                $speed += $rocketSpeed[$i];

                unset($rocketPos[$i]);
                unset($rocketSpeed[$i]);
            }

            $rocketPos[] = $value;
            $rocketSpeed[] = $speed;
        }

        $rocketPos = array_values($rocketPos);
        $rocketSpeed = array_values($rocketSpeed);

        for ($i = 0; $i < count($rocketPos); $i++) {
            $rocketPos[$i] += $rocketSpeed[$i];
        }
    }

    return count($rocketPos);
}

function isMergePossible(array $prevPositions, array $currentPositions): bool
{
    if (count($prevPositions) > count($currentPositions)) {
        return true;
    }

    for ($i = 0; $i < count($prevPositions) - 1; $i++) {
        $prevDistance = $prevPositions[$i + 1] - $prevPositions[$i];
        $currentDistance = $currentPositions[$i + 1] - $currentPositions[$i];
        if ($currentDistance < $prevDistance) {
            return true;
        }
    }

    return false;
}

//echo getResult([3, 11], [5, 1]); // 1
//echo getResult([2, 3], [1, 2]);  // 2
