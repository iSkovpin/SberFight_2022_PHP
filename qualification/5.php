<?php

/*
 * Дан массив чисел и массив арифметических операций (равной длины).
 * Числа всегда перебираются в изначально заданной последовательности.
 * Математические операции могут следовать в любой последовательности и применяются к следующему числу в массиве.
 * Начальное значение перебора равно 0.
 * Найти максимальный результат такого перебора.
 */

function getResult(array $numb, array $arith): float
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

        if ($result === null || $result < $permResult) {
//            echo implode(', ' , $permutation) . ' |= ' , $permResult . "\n";
            $result = $permResult;
        }
    }

    return $result;
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
            $result /= $number;
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

//echo getResult([1, 2, 3, 4, 5, 6, 7, 8, 9, 9], ['+', '-', '-', '*', '//', '+', '-', '-', '*', '//']);
//echo getResult([10, 2, 3, 4, 5, 6, 7, 8, 9, 9], ['+', '-', '+', '-', '+', '-', '+', '-', '*', '//',]);
//echo getResult([1, 2, 3, 4], ['+', '-', '//', '*']);
//echo getResult([3, 4, 12, 0, 1, 5, 25, 4, 5], ['+', '-', '+', '//', '//', '*', '*', '-', '-']);
//echo getResult([1, 4, 2], ['+', '-', '*']);
//echo getResult([3, 4], ['+', '-']);
