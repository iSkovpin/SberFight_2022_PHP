<?php

/*
    Сегодня на турнире сражаются отважные войны! Начальная турнирная сетка определяется случайным образом, количество участников неизменно равно четырем.
    У каждого бойца есть параметр выносливости. Сражаясь, побеждает тот, у кого этот параметр выше. У победителя отнимается выносливость, равная выносливости противника, после чего боец проходит дальше по турнирной сетке.
    Если во время схватки выносливость бойцов одинакова, то побеждает случайный боец, оставаясь с нулевой выносливостью.
    Учитывая случайность подбора в турнирной сетке, определите для каждого участника шанс победить в турнире.

    Ввод:

        fighters_stamina - массив выносливости для каждого участника турнира, length(fighters_stamina)=4, 0<=fighters_stamina[i]<=10

    Вывод:

        Integer[] - шанс победы каждого участника турнира, в процентах, округленных до целого числа (из-за округления может получиться, что сумма процентов не 100, это не страшно - мы все равно поймем кто лучший)

    Пример:

    fighters_stamina = [2, 1, 0, 2]
    getResult(fighters_stamina) = [33, 33, 0, 33]

    Есть три варианта распределения бойцов в турнирной сетке:
    В первом варианте побеждает боец №4
    [2, 1] [0, 2]
        [1, 2]
         [1]
    Во втором - второй боец №2 (при данном вариант турнирной сетки он третий)
    [2, 2] [1, 0]
       [0, 1]
         [1]
    В третьем - боец №1
    [2, 0] [1, 2]
        [2, 1]
          [1]

    У 1, 2 и 4 бойца есть равный шанс победить
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
