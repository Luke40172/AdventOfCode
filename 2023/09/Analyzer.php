<?php

use Utils\Analyzer\LoadAll as LoadAllAnalyzer;

class Analyzer extends LoadAllAnalyzer
{
    private array $dataSet;

    protected function parseLine(string $line): void
    {
        if (empty($line)) {
            return;
        }

        $this->dataSet[] = array_map(static fn($i) => (int) $i, explode(' ', $line));
    }

    protected function postLoadData(): void
    {
    }

    public function parseData(): \Generator
    {
        foreach ($this->dataSet as $dataSet) {
            $history    = [$dataSet];
            $historyIdx = 0;
            do {
                $length = count($history[$historyIdx]);
                $diff   = [];
                for ($i = 0; $i < $length - 1; $i++) {
                    $diff[] = $history[$historyIdx][$i + 1] - $history[$historyIdx][$i];
                }

                $history[] = $diff;
                $historyIdx++;
            }
            while (
                array_reduce($history[$historyIdx], static function ($r, $v) {
                    return $r || ($v !== 0);
                }, false)
            );

            $this->printHistory($history);

            $lastHistoryIdx             = count($history) - 1;
            $history[$lastHistoryIdx][] = 0;
            for ($i = $lastHistoryIdx - 1; $i >= 0; $i--) {
                $lastIdx       = count($history[$i]) - 1;
                $newEntry      = $history[$i][$lastIdx] + $history[$i + 1][$lastIdx];
                $history[$i][] = $newEntry;
            }

            $this->printHistory($history);

            yield $newEntry;
        }
    }

    private function printHistory(array $history): void
    {
        $maxDigits = 2;
        foreach ($history as $idx => $line) {
            $maxSize ??= count($line);
            print (str_pad("", $maxDigits * $idx));
            foreach ($line as $value) {
                printf("%{$maxDigits}d", $value);
                print (str_pad("", $maxDigits));
            }
            print ("\n");
        }
    }
}
