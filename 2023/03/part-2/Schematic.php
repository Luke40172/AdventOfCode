<?php

use Utils\SplStatic2DArray;

class Schematic
{
    private const SYMBOL_STRING = "*=+/&#%-$@";
    private const GEAR_RATIO_SYMBOL = "*";

    private SplStatic2DArray $schema;

    private SplStatic2DArray $lookedAt;
    private int $width;
    private int $height;

    public function __construct($file)
    {
        $this->readSchematic($file);
    }

    public function findNumbers(): \Generator
    {
        // $count = 0;
        for ($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                if (strpos(self::SYMBOL_STRING, $this->schema[$row][$col]) === false) {
                    continue;
                }

                yield from $this->lookAround($row, $col);
            }
        }
    }

    public function findGearRatios(): \Generator
    {
        for ($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                if (self::GEAR_RATIO_SYMBOL !== $this->schema[$row][$col]) {
                    continue;
                }

                $ratio = 1;
                $count = 0;
                foreach ($this->lookAround($row, $col) as $number) {
                    $ratio *= (int) $number;
                    $count++;
                }

                if ($count > 1) {
                    yield $ratio;
                }
            }
        }
    }

    private function lookAround(int $row, int $col): \Generator
    {
        for ($rowOffset = -1; $rowOffset <= 1; $rowOffset++) {
            for ($colOffset = -1; $colOffset <= 1; $colOffset++) {
                if ($rowOffset == 0 && $colOffset == 0) {
                    continue;
                }

                $rowIdx = $row + $rowOffset;
                $colIdx = $col + $colOffset;

                if ($rowIdx < 0 || $rowIdx > $this->height - 1) {
                    continue;
                }

                if ($colIdx < 0 || $colIdx > $this->width - 1) {
                    continue;
                }

                if ($this->lookedAt[$rowIdx][$colIdx]) {
                    continue;
                }

                $number = $this->lookAtDirection(
                    $rowIdx,
                    $colIdx,
                    -1
                )
                    . $this->lookAtDirection(
                        $rowIdx,
                        $colIdx,
                        1
                    )
                ;

                if ($number) {
                    yield $number;
                }
            }
        }
    }

    private function lookAtDirection($row, $col, $dir): string
    {
        if ($row < 0 || $row > $this->height - 1) {
            return "";
        }

        if ($col < 0 || $col > $this->width - 1) {
            return "";
        }

        $number = "";

        do {
            $char = $this->schema[$row][$col];

            $isDot      = $char == '.';
            $isSymbol   = strpos(self::SYMBOL_STRING, $char) !== false;
            $isLookedAt = (bool) $this->lookedAt[$row][$col];

            $this->lookedAt[$row][$col] = true;

            if ($isDot || $isSymbol) {
                break;
            }

            if (!$isDot && !$isSymbol && !$isLookedAt) {
                $number = $dir > 0 ? $number . $char : $char . $number;
            }

            $col += $dir;

            $isInRange = $col >= 0 && $col < $this->width;
            if (!$isInRange) {
                break;
            }

        } while (!$isDot && !$isSymbol && $isInRange);

        return $number;
    }


    private function readSchematic($file): void
    {
        $input = file_get_contents($file);
        if (!$input) {
            die('Unable to load puzzle input');
        }

        $inputSize    = strlen($input);
        $this->height = $lineCount = substr_count($input, "\n");
        $this->width = $lineWidth = strpos($input, "\n");

        $this->schema   = new SplStatic2DArray($lineCount, $lineWidth);
        $this->lookedAt = new SplStatic2DArray($lineCount, $lineWidth);

        $idx = 0;
        $row = 0;
        $col = 0;

        while ($idx < $inputSize - 1) {
            $char = $input[$idx];
            if ($char == "\n") {
                $col = 0;
                $row++;
                $idx++;
                continue;
            }
            $this->schema[$row][$col] = $char;
            $idx++;
            $col++;
        }
    }
}
