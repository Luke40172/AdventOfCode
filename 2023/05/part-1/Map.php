<?php

class Map
{
    private ?Map $destinationMap = null;

    private array $rules = [];


    public function __construct(public readonly string $name)
    {
    }

    public function addRule($destStart, $srcStart, $range)
    {
        $this->rules[] = [
            'dest' => [
                'min' => $destStart,
                'max' => $destStart + $range - 1,
            ],
            'src' => [
                'min' => $srcStart,
                'max' => $srcStart + $range - 1,
            ],
        ];
    }

    public function setDestination(Map &$destinationMap)
    {
        $this->destinationMap = $destinationMap;
    }

    public function findDestination($srcId)
    {
        $rule = array_reduce($this->rules, function ($carry, $rule) use ($srcId) {
            if ($srcId >= $rule['src']['min'] && $srcId <= $rule['src']['max']) {
                $carry = $rule;
            }
            return $carry;
        }, []);

        if (!$rule) {
            $destId = $srcId;
        } else {
            $diff   = $srcId - $rule['src']['min'];
            $destId = $rule['dest']['min'] + $diff;
        }

        // printf("%15s %10d => %10d %s\n", $this->name, $srcId, $destId, $this->destinationMap?->name ?? 'final');

        if ($this->destinationMap) {
            return $this->destinationMap->findDestination($destId);
        }

        return $destId;

    }
}
