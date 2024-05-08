<?php

require ('../../../utils/autoload.php');

$analyzer = new Analyzer('../input');
$sum      = 0;

foreach ($analyzer->getWonCards() as $count) {
    echo "Found: " . $count . "\n";
    $sum += $count;
}

echo "Sum: " . $sum . "\n";
