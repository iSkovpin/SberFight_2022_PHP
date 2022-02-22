<?php

/*
    Турнир.
    Битвы 1х1.
    4 игрока (всего 2 тура до финала).
    Начальная комбинация игроков может быть любой.
    Выигрывает игрок у которого больше стамины. Затем из его стамины вычитается количество стамины побежденного игрока.
    Если у игроков одинаково стамины - с вероятностью 50/50 один из них проходит в следующий тур с 0 стамины.
    Нужно вычислить вероятность выигрыша каждого игрока в процентах (округл. до целых).
 */

class Fighter
{
    public int $index;
    public int $stamina;
    public int $maxStamina;

    public function __construct(int $index, int $maxStamina)
    {
        $this->index = $index;
        $this->maxStamina = $maxStamina;
        $this->restoreStamina();
    }

    public function restoreStamina(): void
    {
        $this->stamina = $this->maxStamina;
    }

    public function hit(int $damage): void
    {
        $this->stamina -= $damage;
    }
}

class FightersSet
{
    /** @var Fighter[] */
    public array $set = [];

    public function __construct(array $set = [])
    {
        /** @var Fighter $fighter */
        foreach ($set as $fighter) {
            $this->add($fighter);
        }
    }

    public function length(): int
    {
        return count($this->set);
    }

    public function add(Fighter $fighter)
    {
        $this->set[] = $fighter;
    }

    public function getStamina(): int
    {
        return $this->set[0]->stamina;
    }

    public function hit(int $damage)
    {
        foreach ($this->set as $fighter) {
            $fighter->hit($damage);
        }
    }

    public static function merge(FightersSet $set1, FightersSet $set2): FightersSet
    {
        $set = new FightersSet();

        foreach ($set1->set as $fighter) {
            $set->add($fighter);
        }

        foreach ($set2->set as $fighter) {
            $set->add($fighter);
        }

        return $set;
    }
}

function getResult(array $fightersStamina): array
{
    $fighters = [];
    foreach ($fightersStamina as $idx => $stamina) {
        $fighters[] = new Fighter($idx, $stamina);
    }

    $combinations = [
        [0, 1, 2, 3],
        [0, 2, 1, 3],
        [0, 3, 2, 1],
    ];

    $totalScores = array_fill(0, 4, 0);

    foreach ($combinations as $combination) {
        $tour1Sets = [];
        foreach ($combination as $fighterIdx) {
            $fighter = $fighters[$fighterIdx];
            $fighter->restoreStamina();
            $tour1Sets[] = new FightersSet([$fighter]);
        }

        $tour2Sets = [];
        $tour2Sets[] = fight($tour1Sets[0], $tour1Sets[1]);
        $tour2Sets[] = fight($tour1Sets[2], $tour1Sets[3]);

        $winnerSet = fight($tour2Sets[0], $tour2Sets[1]);

        foreach ($winnerSet->set as $fighter) {
            $totalScores[$fighter->index] += (100 / 3) / $winnerSet->length();
        }
    }

    array_walk($totalScores, function (&$score) {
        $score = round($score);
    });

    return $totalScores;
}

function fight(FightersSet $set1, FightersSet $set2): FightersSet
{
    if ($set1->getStamina() < $set2->getStamina()) {
        $set2->hit($set1->getStamina());
        return $set2;
    } elseif ($set1->getStamina() > $set2->getStamina()) {
        $set1->hit($set2->getStamina());
        return $set1;
    }

    $set2->hit($set1->getStamina());
    $set1->hit($set1->getStamina());
    return FightersSet::merge($set2, $set1);
}

//print_r(getResult([2, 1, 0, 2])); // 33, 33, 0, 33
//print_r(getResult([1, 0, 3, 4])); // 17, 0, 17, 67
