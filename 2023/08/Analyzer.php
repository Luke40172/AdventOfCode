<?php

use Utils\Analyzer\LoadAll as LoadAllAnalyzer;

class Analyzer extends LoadAllAnalyzer
{
    private array $nodeList;
    private array $startNodes;
    private array $exitNodes;

    private array $instructionLoop;

    protected function parseLine(string $line): void
    {
        if (empty($line)) {
            return;
        }

        if (strpos($line, '=') === false) {
            $this->instructionLoop = str_split($line);
            return;
        }

        preg_match("/(\w+) = \((\w+), (\w+)\)/", $line, $matches);
        $this->nodeList[$matches[1]] = ['L' => $matches[2], 'R' => $matches[3]];
    }

    protected function postLoadData(): void
    {
        foreach ($this->nodeList as $node => $path) {
            $lastChar = substr($node, 2, 1);
            if ($lastChar === 'A') {
                $this->startNodes[$node] = $node;
                continue;
            }

            if ($lastChar === 'Z') {
                $this->exitNodes[$node] = $node;
                continue;
            }
        }

        // var_dump($this->startNodes, $this->exitNodes);
        // exit;
    }

    public function getSteps(): int
    {
        $steps            = 0;
        $node             = 'AAA';
        $instructionIdx   = 0;
        $instructionCount = count($this->instructionLoop);
        while ($node !== 'ZZZ') {
            $steps++;
            $instruction = $this->instructionLoop[$instructionIdx];

            $node = $this->nodeList[$node][$instruction];

            $instructionIdx++;
            if ($instructionIdx >= $instructionCount) {
                $instructionIdx = 0;
            }
        }
        return $steps;
    }

    public function getStepsToExitNode(string $node): int
    {
        $steps            = 0;
        $instructionIdx   = 0;
        $instructionCount = count($this->instructionLoop);
        while (!isset($this->exitNodes[$node])) {
            $steps++;
            $instruction = $this->instructionLoop[$instructionIdx];

            $node = $this->nodeList[$node][$instruction];

            $instructionIdx++;
            if ($instructionIdx >= $instructionCount) {
                $instructionIdx = 0;
            }
        }
        return $steps;
    }

    public function getSteps2(): int|float
    {
        $break            = false;
        $instructionIdx   = 0;
        $instructionCount = count($this->instructionLoop);

        $currentNodeList = $this->startNodes;

        $steps = [];
        foreach ($this->startNodes as $node) {
            $steps[] = $this->getStepsToExitNode($node);
        }

        $stepCount = count($steps);

        $remainder     = $steps;
        $matchedPrimes = [];

        foreach ($this->getPrimeNumbers() as $primeNumber) {

            foreach ($remainder as $idx => &$value) {
                $iterationCount = 0;
                while ($value > 1 && $value % $primeNumber === 0) {
                    $iterationCount++;
                    $value /= $primeNumber;
                }

                if ($iterationCount > 0) {
                    $matchedPrimes[$primeNumber] = max($matchedPrimes[$primeNumber] ?? 0, $iterationCount);
                }
            }

            if (array_sum($remainder) <= $stepCount) {
                break;
            }

            if ($primeNumber > 300000) {
                var_dump($remainder);
                die('Failure');
            }
        }

        $lcm = 1;
        foreach ($matchedPrimes as $primeNumber => $exponent) {

            $lcm *= pow($primeNumber, $exponent);
        }

        return $lcm;
    }

    private function getPrimeNumbers(): \Generator
    {
        $primeNumbers = [
            2,
            3,
            5,
            7,
            11,
            13,
            17,
            19,
            23,
            29,
            31,
            37,
            41,
            43,
            47,
            53,
            59,
            61,
            67,
            71,
            73,
            79,
            83,
            89,
            97,
            101,
            103,
            107,
            109,
            113,
            127,
            131,
            137,
            139,
            149,
            151,
            157,
            163,
            167,
            173,
            179,
            181,
            191,
            193,
            197,
            199,
            211,
            223,
            227,
            229,
            233,
            239,
            241,
            251,
            257,
            263,
            269,
            271,
            277,
            281,
            283,
            293,
            307,
            311,
            313,
            317,
            331,
            337,
            347,
            349,
            353,
            359,
            367,
            373,
            379,
            383,
            389,
            397,
            401,
            409,
            419,
            421,
            431,
            433,
            439,
            443,
            449,
            457,
            461,
            463,
            467,
            479,
            487,
            491,
            499,
            503,
            509,
            521,
            523,
            541,
            547,
            557,
            563,
            569,
            571,
            577,
            587,
            593,
            599,
            601,
            607,
            613,
            617,
            619,
            631,
            641,
            643,
            647,
            653,
            659,
            661,
            673,
            677,
            683,
            691,
            701,
            709,
            719,
            727,
            733,
            739,
            743,
            751,
            757,
            761,
            769,
            773,
            787,
            797,
            809,
            811,
            821,
            823,
            827,
            829,
            839,
            853,
            857,
            859,
            863,
            877,
            881,
            883,
            887,
            907,
            911,
            919,
            929,
            937,
            941,
            947,
            953,
            967,
            971,
            977,
            983,
            991,
            997,
            1009,
            1013,
            1019,
            1021,
            1031,
            1033,
            1039,
            1049,
            1051,
            1061,
            1063,
            1069,
            1087,
            1091,
            1093,
            1097,
            1103,
            1109,
            1117,
            1123,
            1129,
            1151,
            1153,
            1163,
            1171,
            1181,
            1187,
            1193,
            1201,
            1213,
            1217,
            1223,
        ];

        foreach ($primeNumbers as $number) {
            yield $number;
        }
    }
}
