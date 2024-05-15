<?php

use Utils\Analyzer\LoadAll as LoadAllAnalyzer;

class Analyzer extends LoadAllAnalyzer
{
    private Node $rootNode;
    private array $nodeList;

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
}
