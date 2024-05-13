<?php

require ('../../../utils/autoload.php');

$analyzer = new RaceAnalyzer(env('DEV', false) ? '../example.input' : '../input');
$score    = 1;

foreach ($analyzer->findPossibleFasterTimes() as $count) {
    echo "Found: " . $count . "\n";
    $score *= $count;
}

echo "Score: " . $score . "\n";

