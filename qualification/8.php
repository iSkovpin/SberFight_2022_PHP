<?php

/*
    Америка 90-х годов. Повсеместны синдикаты и картельные сговоры. У вас на руках список торговых сделок крупных компаний за последний год.
    Если сделки состоят из цепочки, в которой первая компания и последняя совпадают, образуя круг компаний, то вы наткнулись на очередной синдикат. Например, если компания A заключила сделку с B, та в свою очередь с C, а C - с A, то перед вами незаконный торговый союз.
    Найдите все такие связи и выведите количество синдикатов. То есть необходимо найти количество уникальных цепочек, в которых первая и последняя компании совпадают. Отметим, что цепочки с одинаковым набором, например, a-b-c-a и b-c-a-b - это один и тот же синдикат.
    Случаев, когда компании заключали сделки напрямую ("a-b", "b-a") нет.

    Подсказка: Если нарисовать все связи с помощью однонаправленного графа, то количество синдикатов - это количество петель в этом графе.

     Ввод:
    deal - массив строк deal["a-bc..."], где a - компания, заключившая сделку с b,c… - компании, с которыми была совершена сделка (количество таких компаний не ограничено). Если у какой-либо компании после названия нет тире и далее списка, то у компании нет никаких сделок. 0<length(deal)<=10

     Вывод:
    integer - количество синдикатов

    Examples:

    deal = ["a-b", "b-c", "c-a"]
    getResult(deal) = 1

    deal = ["a-b","b-c","c-ad","d"]
    getResult(deal) = 1
 */

class Company
{
    public string $letter;

    /** @var Company[] */
    public array $deals = [];

    public function __construct(string $letter)
    {
        $this->letter = $letter;
    }

    public function addDealWith(Company $company)
    {
        $this->deals[$company->letter] = $company;
    }
}

function getResult(array $deal): int
{
    /** @var Company[] $companies */
    $companies = [];
    $getCompany = function (array &$companies, string $letter) {
        if (isset($companies[$letter])) {
            return $companies[$letter];
        }
        $companies[$letter] = new Company($letter);
        return $companies[$letter];
    };

    // build a graph
    foreach ($deal as $d) {
        if (substr_count($d, '-')) {
            [$from, $to] = explode('-', $d);
            $to = str_split($to);
        } else {
            $from = $d;
            $to = [];
        }

        $fromCompany = $getCompany($companies, $from);

        foreach ($to as $toCompanyL) {
            $toCompany = $getCompany($companies, $toCompanyL);
            $fromCompany->addDealWith($toCompany);
        }
    }

    // walk around the graph
    $circles = [];
    foreach ($companies as $company) {
        circleSearch($company, null, [], $circles);
    }

    return count($circles);
}

function circleSearch(Company $root, ?Company $current, array $path, array &$circles): void
{
    if ($current !== null && in_array($current->letter, $path)) {
        if ($root->letter === $current->letter) {
            sort($path);
            $pKey = implode($path);
            $circles[$pKey] = $pKey;
        }
        return;
    }

    $workWith = $current ?? $root;
    $path[] = $workWith->letter;

    foreach ($workWith->deals as $dealCompany) {
        circleSearch($root, $dealCompany, $path, $circles);
    }
}

//echo getResult(['a-b', 'b-c', 'c-a']) . "\n";          // 1
//echo getResult(['a-b', 'b-c', 'c-ad', 'd']) . "\n";    // 1
//echo getResult(['a-b', 'b-c', 'c-ad', 'd-a']) . "\n";  // 2
//echo getResult(['a-b', 'b-c', 'c-d', 'd']) . "\n";     // 0
//echo getResult(['a-b', 'b', 'c-b']) . "\n";            // 0
//echo getResult(['a-b', 'b-c', 'c-ade', 'd-a', 'e-f', 'f-b']) . "\n";          // 3
//echo getResult(['a-b', 'b-c', 'c-ade', 'd-a', 'e-f', 'f-bm', 'm-a']) . "\n";  // 4
