<?php

class DigitMatcher
{
    private const DIGIT_NAMES = [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4,
        'five' => 5,
        'six' => 6,
        'seven' => 7,
        'eight' => 8,
        'nine' => 9,
    ];

    private const TREE_LEAF = '_keyword_';
    private const TREE_LABEL = '_label_';
    private const TREE_DEPTH = '_depth_';
    private array $trieDict = [];

    public function __construct()
    {
        for ($i = 1; $i <= 9; $i++) {
            $this->addKeyword((string) $i, $i);
        }
        foreach (self::DIGIT_NAMES as $name => $value) {
            $this->addKeyword($name, $value);
        }
        // var_dump($this->trieDict);
    }

    public string $latestSentence = '';
    private array $matches = [];

    public function extractDigits(string $sentence): array
    {
        $matchedKeywords = [];
        $this->matches   = [];

        $currentDict = &$this->trieDict;
        $idx         = 0;
        $sentenceLen = strlen($sentence);

        while ($idx < $sentenceLen) {
            $char = $sentence[$idx];

            if (!isset($currentDict[$char])) {
                $idx -= $currentDict[self::TREE_DEPTH] - 1;
                $currentDict = &$this->trieDict;
                continue;
            }

            $currentDict = &$currentDict[$char];

            if (isset($currentDict[self::TREE_LEAF])) {
                $matchedKeywords[] = $currentDict[self::TREE_LEAF];
                $this->matches[]   = [
                    'pos' => $idx - strlen($currentDict[self::TREE_LABEL]) + 1,
                    'len' => strlen($currentDict[self::TREE_LABEL]),
                ];

                if (strlen($currentDict[self::TREE_LABEL]) == 1) {
                    $idx++;
                } else {
                    $idx--;
                }
                $currentDict = &$this->trieDict;
                continue;
            }

            $idx++;
        }

        $firstMatch = current($this->matches);
        $lastMatch  = end($this->matches);

        if ($firstMatch['pos'] == $lastMatch['pos']) {
            $sentence = substr($sentence, 0, $firstMatch['pos'])
                . "\033[32m" . substr($sentence, $firstMatch['pos'], $firstMatch['len']) . "\033[0m"
                . substr($sentence, $lastMatch['pos'] + $lastMatch['len']);
        } else {
            $sentence = substr($sentence, 0, $firstMatch['pos'])
                . "\033[32m" . substr($sentence, $firstMatch['pos'], $firstMatch['len']) . "\033[0m"
                . substr($sentence, $firstMatch['pos'] + $firstMatch['len'], $lastMatch['pos'] - ($firstMatch['pos'] + $firstMatch['len']))
                . "\033[32m" . substr($sentence, $lastMatch['pos'], $lastMatch['len']) . "\033[0m"
                . substr($sentence, $lastMatch['pos'] + $lastMatch['len']);

        }
        $this->latestSentence = $sentence;

        return $matchedKeywords;
    }

    private function addKeyword(string $digit, int $value)
    {
        $currentDict = &$this->trieDict;

        $chars = str_split($digit);
        foreach ($chars as $idx => $char) {
            if (!isset($currentDict[$char])) {
                $currentDict[$char]            = [];
                $currentDict[self::TREE_DEPTH] = $idx;
            }
            $currentDict = &$currentDict[$char];
        }

        $currentDict[self::TREE_LABEL] = $digit;
        $currentDict[self::TREE_LEAF]  = $value;

    }

}
