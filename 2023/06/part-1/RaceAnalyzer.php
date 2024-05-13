<?php

class RaceAnalyzer
{
    private $handle;

    private array $raceList;

    public function __construct(string $file)
    {
        $this->loadData($file);
    }

    private function loadData(string $file): void
    {
        $this->handle = fopen($file, 'r');
        if (!$this->handle) {
            die('Unable to load puzzle input');
        }

        $timeList     = $this->parseLine(fgets($this->handle));
        $distanceList = $this->parseLine(fgets($this->handle));

        $this->raceList = array_map(
            static fn($time, $distance) => ['t' => $time, 'd' => $distance],
            $timeList,
            $distanceList
        );

        fclose($this->handle);
    }

    private function parseLine(string $line): array
    {
        $line         = trim($line, "\n");
        $line         = preg_replace("/ +/", ' ', $line);
        list(, $line) = explode(': ', $line);
        return explode(' ', $line);
    }


    public function findPossibleFasterTimes(): \Generator
    {
        foreach ($this->raceList as $race) {
            $time     = $race['t'];
            $distance = $race['d'];
            $count    = 0;
            var_dump($race);
            for ($t = 0; $t <= $time; $t++) {
                $d = ($t * 1) * ($time - $t);
                // var_dump($d, $distance);
                if ($d > $distance) {
                    $count++;
                }
            }
            yield $count;
        }
    }
}
