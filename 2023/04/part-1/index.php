<?php

require ('../../../utils/autoload.php');

$analyzer = new Analyzer('../input');
$sum      = 0;

foreach ($analyzer->getScores() as $score) {
    echo "Found: " . $score . "\n";
    $sum += $score;
}

echo "Sum: " . $sum . "\n";
