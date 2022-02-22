<?php

/*
    Сегодня Аристократия организовывает пир. Мы знаем количество гостей, ваша задача рассадить всех за стол.
    Однако, некоторые гости дали вам список неприятелей, с которыми они не сядут.
    Стулья расставили так, что у стола оказалось два крайних места, у которых только один соседний гость. В остальных случаях соседа два.
    Определите, можно ли рассадить гостей так, чтобы все оказались довольны.

    Ввод:

    invited_list - количество приглашённых гостей, 0 < invited_list < 10
    dislike_list - строчный массив неприятелей, ["1-2,3"] - означает, что гость под номером 1 не сядет с гостями 2 и 3

    Вывод:

    Boolean - возможно ли рассадить гостей так, чтобы они все были довольны

    Пример:

    invited_list = 4
    dislike_list = ["1-2", "3-4"]
    getResult(invited_list, dislike_list) = True // [1, 4, 2, 3]
 */

/**
 * Implement function getResult
 */
function getResult(int $invitedList, array $dislikeList): bool
{
    $guests = range(1, $invitedList);

    $incompatiblePairs = [];
    foreach ($dislikeList as $value) {
        $parts = explode('-', $value);
        $dislikes = explode(',', $parts[1]);
        $guest = $parts[0];

        foreach ($dislikes as $dislike) {
            $pair = [(int)$guest, (int)$dislike];
            sort($pair);
            if (!in_array($pair, $incompatiblePairs)) {
                $incompatiblePairs[] = $pair;
            }
        }
    }

    $tempArr = [];
    foreach ($guests as $guest) {
        $tempArr[] = [$guest];
    }

    $continue = true;
    while ($continue) {
        $continue = false;

        for ($i = 0; $i < $invitedList; $i++) {
            for ($j = 0; $j < $invitedList; $j++) {
                if (in_array($guests[$j], $tempArr[$i])) {
                    continue;
                }
                $pair = [$tempArr[$i][array_key_last($tempArr[$i])], $guests[$j]];
                sort($pair);
                if (in_array($pair, $incompatiblePairs)) {
                    continue;
                }
                $tempArr[$i][] = $guests[$j];

                if (count($tempArr[$i]) === $invitedList) {
                    return true;
                }

                $continue = true;
            }
        }
    }

    return false;
}
