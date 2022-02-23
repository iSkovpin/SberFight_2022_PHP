<?php

/*
    Вам даётся массив имён трёх братьев и массив утверждений, которые являются правдивыми. Список возможных утверждений:

        "is youngest" – самый младший
        "is not youngest" – не самый младший
        "is not oldest" – не самый старший
        "is oldest" – самый старший
        Вам предстоит расставить всех братьев по возрастанию возраста

     Ввод:

        names – массив имён трёх братьев, length(names)=3
        statements – массив(string[]), statements[i] = ["a-b"], где a – имя брата, b – утверждение, их разделяет тире без пробелов. Для одного брата может быть несколько утверждений

     Вывод:

        String[] – список братьев от самого младшего до самого старшего, решение всегда есть и всегда одно

    Example:

    names=["Kevin", "Jack", "Mark"]
    statements=["Kevin-is not youngest", "Jack-is oldest", "Mark-is not oldest"]
    getResult(names, statements)=["Mark", "Kevin", "Jack"]
 */

function getResult(array $names, array $statements): array
{
    $statementsMap = array_flip($names);
    foreach ($statementsMap as &$stmts) {
        $stmts = [];
    }

    foreach ($statements as $statement) {
        [$name, $stmnt] = explode('-', $statement);
        $statementsMap[$name][] = $stmnt;
    }

    $candidates = [$names, $names, $names];
    foreach ($statementsMap as $stmntName => $stmnts) {
        foreach ($stmnts as $stmnt) {
            switch ($stmnt) {
                case "is youngest":
                    updCandidates($candidates, $stmntName, 0, true);
                    break;
                case "is not youngest":
                    updCandidates($candidates, $stmntName, 0, false);
                    break;
                case "is not oldest":
                    updCandidates($candidates, $stmntName, 2, false);
                    break;
                case "is oldest":
                    updCandidates($candidates, $stmntName, 2, true);
                    break;
            }
        }
    }

    clearCandidates($candidates, $names);

//    print_r($statementsMap);
//    print_r($ageMap);
//    print_r($candidates);

    array_walk($candidates, function (&$list) {
        $list = $list[array_key_first($list)];
    });

    return $candidates;
}

function updCandidates(array &$candidates, string $name, int $position, bool $positive): void
{
    for ($i = 0; $i < count($candidates); $i++) {
        foreach ($candidates[$i] as $key => $cndName) {
                if (($positive && $name !== $cndName && $i === $position)
                    || ($positive && $name === $cndName && $i !== $position)
                    || (!$positive && $name === $cndName && $i === $position)) {
                    unset($candidates[$i][$key]);
                }
        }
    }
}

function clearCandidates(array &$candidates, array $names): void
{
    foreach ($names as $name) {
        $count = 0;
        $position = -1;
        foreach ($candidates as $pos => $list) {
            if (in_array($name, $list)) {
                $count++;
                $position = $pos;
            }
        }

        if ($count === 1) {
            updCandidates($candidates, $name, $position, true);
        }
    }
}

// print_r(getResult(["Kevin", "Jack", "Mark"], ["Kevin-is not youngest", "Jack-is oldest", "Mark-is not oldest"])); // ["Mark", "Kevin", "Jack"]
