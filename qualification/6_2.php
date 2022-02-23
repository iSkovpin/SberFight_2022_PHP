<?php

/*
    Пришло время праздника! На корпоратив сотрудники компании решили добираться на такси, но решили вызвать как можно меньше машин, чтобы было дешевле.
    Они посчитали, что на переднем сидении может сидеть человек какого угодно веса, а на задних - до 210 кг в сумме и до трех человек.
    Какое минимальное число машин необходимо вызвать, чтобы всех довезти на корпоратив?

    На входе:

        weight - массив чисел, вес каждого сотрудника компании, 0<length(weight)<25, 0<weight[i]<=210

    На выходе:

        integer - количество машин

    Пример:

    weight=[89, 46, 134, 78, 79, 67, 63, 81]
    getResult(weight) → 2 // 134 + 79, 67, 63; 89+78, 81, 46
 */

/**
 * Implement function getResult
 */
function getResult(array $weight): int
{
    sort($weight);
    $minCars = ceil(count($weight) / 4);
    $carsNum = $minCars;

    while (!tryCars($carsNum, $weight)) {
        $carsNum++;
    }

    return $carsNum;
}


function tryCars(int $num, array $weight) {
    $cars = [];
    for ($i = 0; $i < $num; $i++) {
        $cars[$i] = array_fill(0, 4, 0);
    }

    $sit = true;
    $w = array_pop($weight);
    while ($w && $sit) {
        $sit = false;
        foreach ($cars as &$car) {
            if (addPerson($car, $w)) {
                $sit = true;
                $w = array_pop($weight);
                if ($w === null) {
                    break;
                }
            }
        }
    }

    if ($w) {
        return false;
    }

    return true;
}


function addPerson(&$car, $weight): bool
{
    if ($car[0] === 0) {
        $car[0] = $weight;
        return true;
    }

    if ($car[3] !== 0 || ($car[1] + $car[2] + $weight) > 210) {
        return false;
    }

    for ($i = 0; $i < 4; $i++) {
        if ($car[$i] === 0) {
            $car[$i] = $weight;
            break;
        }
    }

    return true;
}

//echo getResult([89, 46, 134, 78, 79, 67, 63, 81]);      //2
//echo getResult([89, 46, 134, 78, 79, 67, 63, 81, 66]);  //3
//echo getResult([210, 210, 210]);                        //2
