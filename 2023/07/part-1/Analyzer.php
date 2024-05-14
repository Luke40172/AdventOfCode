<?php

class Analyzer
{
    private array $list;

    private const DENOM_VALUES = [
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        'T' => 10,
        'J' => 11,
        'Q' => 12,
        'K' => 13,
        'A' => 14,
    ];

    public function __construct(string $file)
    {
        $handle = fopen($file, 'r');
        if (!$handle) {
            die('Unable to load puzzle input');
        }

        $this->loadData($handle);

        fclose($handle);
    }

    private function loadData($handle): void
    {
        while ($line = fgets($handle)) {
            list($hand, $bid) = explode(' ', trim($line, "\n"));
            $this->list[]     = [
                'hand' => $hand,
                'bid' => $bid,
                'value' => $this->getValue($hand),
                'sorted_hand' => $hand,
            ];
        }

        usort($this->list, static function ($a, $b) {
            return $a['value'] == $b['value'] ? 0 : ($a['value'] > $b['value'] ? 1 : -1);
        });
    }

    private function getValue(string &$hand): int
    {
        $comboList = [];
        $cards     = str_split($hand);
        foreach ($cards as $idx => $denomination) {
            $denominationValue             = self::DENOM_VALUES[$denomination];
            $cards[$idx]                   = $denominationValue;
            $comboList[$denominationValue] ??= ['count' => 0, 'denom' => $denomination, 'value' => $denominationValue];
            $comboList[$denominationValue]['count']++;
        }

        usort($comboList, static function ($a, $b) {
            if ($a['count'] === $b['count']) {
                return $a['value'] === $b['value'] ? 0 : ($a['value'] < $b['value'] ? 1 : -1);
            }

            return $a['count'] < $b['count'] ? 1 : -1;
        });

        $hand = array_reduce($comboList, fn($c, $v) => ($c .= str_repeat($v['denom'], $v['count'])), '');

        $type  = 0;
        $value = 0;

        // printf("%5s\n", $hand);

        for ($i = 0; $i < 5; $i++) {
            if ($i > 0) {
                $type  = $type << 3;
                $value = $value << 4;
            }
            $value += ($cards[$i] - 2);

            if (!isset($comboList[$i])) {
                continue;
            }
            $type += ($comboList[$i]['count'] - 1);

            // printf("%2d => %10d = %-15s%-20s = %-d\n", $comboList[$i]['count'] - 1, $type, decbin($type), decbin($value), $value);
        }
        // printf("%2d => %10d = %015s%020s = %-d\n", 0, $type, decbin($type), decbin($value), $value);

        $handValue = $type;
        $handValue = $handValue << 20;
        $handValue += $value;
        // printf("      %10d = %035s\n\n", $handValue, decbin($handValue));

        return $handValue;
    }

    public function getWinnings(): \Generator
    {
        $this->printList();
        // var_dump($this->list);
        foreach ($this->list as $rank => $hand) {
            yield $hand['bid'] * ($rank + 1);
        }
    }

    public function printList()
    {
        foreach ($this->list as $rank => $hand) {
            printf("%10d => %s %s %d\n", $rank + 1, $hand['hand'], $hand['sorted_hand'], $hand['value']);
        }
        // printf("%2d => %10d = %015s%020s = %-d\n", 0, $type, decbin($type), decbin($value), $value);
    }
}

/*
001000000000000
001000000000000
*/
