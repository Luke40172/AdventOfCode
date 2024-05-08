<?php

class Analyzer
{
    private $handle;

    private array $muliplier = [];

    public function __construct($file)
    {
        $this->handle = fopen($file, 'r');
        if (!$this->handle) {
            die('Unable to load puzzle input');
        }
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    public function getWonCards(): \Generator
    {
        while ($line = fgets($this->handle)) {
            list($game, $data)                       = explode(':', $line);
            list(, $gameId)                          = explode('Card ', $game, 2);
            list($winningNumbers, $availableNumbers) = explode('|', $data);

            $gameId           = (int) $gameId;
            $availableNumbers = str_split(trim($availableNumbers, "\n"), 3);

            $count = 0;
            foreach ($availableNumbers as $number) {
                if (strpos($winningNumbers, $number . " ") !== false) {
                    $count++;
                }
            }

            for ($i = 1; $i <= $count; $i++) {
                if (!isset($this->muliplier[$gameId + $i])) {
                    $this->muliplier[$gameId + $i] = 1;
                }
                $this->muliplier[$gameId + $i] += ($this->muliplier[$gameId] ?? 1);
            }

            yield 1 * ($this->muliplier[$gameId] ?? 1);
        }
    }
}
