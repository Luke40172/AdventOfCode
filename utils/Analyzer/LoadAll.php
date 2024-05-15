<?php

namespace Utils\Analyzer;

abstract class LoadAll
{
    private array $list;

    final public function __construct(string $file)
    {
        $handle = fopen($file, 'r');
        if (!$handle) {
            die('Unable to load puzzle input');
        }

        $this->loadData($handle);

        fclose($handle);
    }

    final protected function loadData($handle): void
    {
        while ($line = fgets($handle)) {
            $this->parseLine(trim($line));
        }

        $this->postLoadData();
    }

    abstract protected function parseLine(string $line): void;
    abstract protected function postLoadData(): void;
}
