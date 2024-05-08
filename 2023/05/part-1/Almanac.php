<?php

class Almanac
{
    private $handle;

    private array $seedList = [];
    private array $mapList = [];

    public function __construct($file)
    {
        $this->handle = fopen($file, 'r');
        if (!$this->handle) {
            die('Unable to load puzzle input');
        }

        $this->loadSeedsAndMaps();
    }

    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }

    private function loadSeedsAndMaps(): void
    {
        while ($line = fgets($this->handle)) {
            // echo $line;
            if (strpos($line, "seeds:") !== false) {
                $this->loadSeeds($line);
                continue;
            }

            if (strpos($line, ' map:') !== false) {
                $line = str_replace(" map:\n", '', $line);

                list($name, , $relatedToName) = explode('-', $line);

                $this->loadMap($name, $relatedToName);
            }
        }
    }

    private function loadSeeds(string $line): void
    {
        list(, $seedString) = explode(': ', trim($line));
        $this->seedList     = explode(' ', $seedString);
    }

    private function loadMap($name, $relatedToName): void
    {
        if (!isset($this->mapList[$name])) {
            $this->mapList[$name] = new Map($name);
        }

        if (!isset($this->mapList[$relatedToName])) {
            $this->mapList[$relatedToName] = new Map($relatedToName);
        }

        $map = &$this->mapList[$name];
        $map->setDestination($this->mapList[$relatedToName]);

        while ($line = fgets($this->handle)) {
            $line = trim($line);
            if ($line === '') {
                break;
            }

            list($destStart, $srcStart, $range) = explode(' ', $line);

            $map->addRule($destStart, $srcStart, $range);
        }
    }

    public function analyzeSeeds(): \Generator
    {
        $map = $this->mapList['seed'];
        foreach ($this->seedList as $seedId) {
            yield $map->findDestination($seedId);
        }
    }

}
