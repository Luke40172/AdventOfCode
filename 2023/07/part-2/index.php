<?php

require ('../../../utils/autoload.php');

$analyzer = new Analyzer(env('DEV', true) ? '../example.input' : '../input');
$sum      = 0;

foreach ($analyzer->getWinnings() as $value) {
    $sum += $value;
}

echo "Sum: " . $sum . "\n";

