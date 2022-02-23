<?php

/*
    Дана строка s, она содержит подстроки c одинаковыми символами, подстроки разделены пробелом.
    Вы можете поэтапно заменять пробелы в строке на любые символы.
    Если между разными последовательностями не окажется пробела, то подстрока с бóльшим количеством символов заменит остальные подстроки. Например, строка "aaabb" становится "aaaaa".
    Создайте максимально крупную подстроку заданного символа. Гарантируется, что одинакового количества разных символов в результате замен у двух подстрок быть не может.

    Ввод:

        s - строка символов, все последовательности разделены пробелом, 1<=length(s)<=100, s[i]=space,a..z
        symbol - заданный символ, length(symbol)=1

    Вывод:

    Integer - количество одинаковых подряд идущих заданных символов

    Пример 1:

    s = "aaa bbb"
    symbol = "a"
    getResult(s, symbol) = 7 // пробел заменяем на "a", тогда получаем "aaaabbb", которая по правилу заменяется на "aaaaaaa" - всего 7 символов "a"

    Пример 2:

    s = "bbbb cc aa"
    symbol = "b"
    getResult(s, symbol) = 10 // первый пробел заменяем на "b", тогда получаем "bbbbbcc aa", которая по правилу заменяется на "bbbbbbb aa", далее второй пробел заменяем на "b", получаем "bbbbbbbbaa" -> "bbbbbbbbbb", всего 10 символов "b"

 */

function getResult(string $s, string $symbol): int
{
    $replaced = true;
    while ($replaced) {
        $replaced = false;
        for ($i = 0; $i < strlen($s); $i++) {
            if ($s[$i] !== ' ') {
                continue;
            }

            $replaced = $replaced || fillSpace($s, $symbol, $i);
        }
    }

    $counter = 0;
    $max = 0;
    for ($i = 0; $i < strlen($s); $i++) {
        if ($s[$i] !== $symbol) {
            $counter = 0;
            continue;
        }
        $counter++;

        if ($max < $counter) {
            $max = $counter;
        }
    }

    return $max;
}

function fillSpace(string &$str, string $symbol, int $pos)
{
    $subPos = $pos + 1;

    $substr2 = [$subPos, ''];
    while (isset($str[$subPos]) && $str[$subPos] !== ' ') {
        $substr2[1] .= $str[$subPos];
        $subPos++;
    }

    $subPos = $pos - 1;
    $substr1 = [$subPos, ''];
    while ($subPos >= 0 && isset($str[$subPos]) && $str[$subPos] !== ' ') {
        $substr1[1] .= $str[$subPos];
        $substr1[0] = $subPos;
        $subPos--;
    }

    $replaceIdx = -1;
    if ($substr1[1][0] === $symbol && strlen($substr1[1]) >= strlen($substr2[1])) {
        $replaceIdx = $substr2[0];
    } elseif ($substr2[1][0] === $symbol && strlen($substr2[1]) >= strlen($substr1[1])) {
        $replaceIdx = $substr1[0];
    }

    if ($replaceIdx === -1) {
        return false;
    }

    $str[$pos] = $symbol;
    while (isset($str[$replaceIdx]) && $str[$replaceIdx] !== ' ') {
        if ($str[$replaceIdx] === $symbol) {
            $replaceIdx++;
            continue;
        }

        $str[$replaceIdx] = $symbol;
    }

    return true;
}

//echo getResult("bbbb cc aa", 'b') . "\n"; // 10
//echo getResult("aaa bbb", 'a'). "\n"; // 7
//echo getResult("a a a b bb ddddddddddddd", 'c'). "\n"; // 0
//echo getResult("a a a b bb ddddddddddddd", 'a'). "\n"; // 10
