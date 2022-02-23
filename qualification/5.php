<?php

/*
    На вход подаётся массив чисел numb. Вам нужно выполнить арифметические действия, которые находятся в строковом массиве arith. В массиве arith находятся знаки: "+", "-", "//" - целочисленное деление, "*". Они означают соответствующие арифметические действия.
    Вы можете использовать знаки в любом порядке, но порядок чисел в numb должен оставаться неизменным - первое число равно всегда нулю, далее numb[0], numb[1], ... Количество знаков всегда равно количеству чисел в массиве. Операции выполняются последовательно, без учета приоритета операций. Будьте внимательнее в операциях с 0//... - они не приводят ни к чему хорошему, игнорируйте их.
    Получите наибольшее возможное число.

    Ввод:

        numb - массив чисел, 1<length(numb)<=10, 0<=numb[i]<=25
        arith - массив возможных операций, arith[i]={"+" | "-" | "//" | "*"}

    Вывод:

        Integer - итоговое наибольшее число, которое получилось в результате всех арифметических действий

    Пример:

    numb = [3, 4]
    arith = ["+", "-"]
    getResult(numb, arith) = 1

    Сначала выгодно из числа 0 вычесть 3 (получается -3), потом прибавить 4.
    Ответ: 1.
 */

function getResult(array $numb, array $arith): int
{
    $arithPermutations = [];
    getUniquePermutations($arith, $arithPermutations);

    $result = null;
    foreach ($arithPermutations as $permutation) {
        try {
            $permResult = getPermResult($numb, $permutation);
        } catch (Exception $e) {
            continue;
        }

        echo implode(', ' , $permutation) . ' |= ' , $permResult . "\n";

        if ($result === null || $result < $permResult) {
//            echo implode(', ' , $permutation) . ' |= ' , $permResult . "\n";
            $result = $permResult;
        }
    }

    return (int) $result;
}

function getPermResult(array $numbers, array $arithOperations): float
{
    $result = 0;
    $len = count($numbers) - 1;

    for ($i = 0; $i <= $len; $i++) {
        $number = $numbers[$i];
        $operation = $arithOperations[$i];

        if ($operation === '+') {
            $result += $number;
        } elseif ($operation === '-') {
            $result -= $number;
        } elseif ($operation === '//') {
            if ($number === 0) {
                throw new Exception('Division by zero');
            }
            $result = intdiv($result, $number);
        } elseif ($operation === '*') {
            $result *= $number;
        }
    }

    return $result;
}

function getUniquePermutations($inputArr, &$returnArr = [], $processedArr = [])
{
    if (count($inputArr) === 1) {
        $permutation = array_merge($processedArr, $inputArr);
        $permKey = implode('', $permutation);
        if (!isset($returnArr[$permKey]) && $permutation[0] !== '//') {
            $returnArr[$permKey] = $permutation;
        }
    } else {
        foreach ($inputArr as $key => $value) {
            $copyArr = $inputArr;
            unset($copyArr[$key]);
            getUniquePermutations($copyArr, $returnArr, array_merge($processedArr, [$key => $value]));
        }
    }
}

//echo getResult([1, 2, 3, 4, 5, 6, 7, 8, 9, 9], ['+', '-', '-', '*', '//', '+', '-', '-', '*', '//']) . "\n";
//echo getResult([10, 2, 3, 4, 5, 6, 7, 8, 9, 9], ['+', '-', '+', '-', '+', '-', '+', '-', '*', '//',]) . "\n";
//echo getResult([1, 2, 3, 4], ['+', '-', '//', '*']) . "\n";
//echo getResult([3, 4, 12, 0, 1, 5, 25, 4, 5], ['+', '-', '+', '//', '//', '*', '*', '-', '-']) . "\n";
//echo getResult([1], ['-']) . "\n";
//echo getResult([1, 4, 2], ['-', '+', '*']) . "\n";
//echo getResult([3, 4], ['+', '-']) . "\n";
