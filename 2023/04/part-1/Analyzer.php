<?php

class Analyzer
{
    private $handle;

    public function __construct($file)
    {
        $this->handle = fopen($file, 'r');
        if (!$this->handle) {
            die('Unable to load puzzle input');
        }
    }

    public function getScores(): \Generator
    {
        while ($line = fgets($this->handle)) {
            list(, $data)                            = explode(':', $line);
            list($winningNumbers, $availableNumbers) = explode('|', $data);

            $availableNumbers = str_split(trim($availableNumbers, "\n"), 3);

            $score = 0;
            foreach ($availableNumbers as $number) {
                if (strpos($winningNumbers, $number . " ") !== false) {
                    $score += ($score == 0 ? 1 : $score);
                }
            }
            yield $score;
        }
    }
}
