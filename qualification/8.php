<?php

/*
 * Дан массив "сделок" между компаниями.
 * Если есть связи вида a->b, b->c, c->a (петля) - это картельный сговор.
 * Нужно посчитать количество таких сговоров.
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
