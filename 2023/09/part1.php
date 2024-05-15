<?php

require ('../../utils/autoload.php');

$analyzer = new Analyzer(env('DEV', false) ? 'example.input' : 'input');
$sum      = 0;

foreach ($analyzer->parseData() as $value) {
    printf("%d\n", $value);
    $sum += $value;
}

echo "Sum: " . $sum . "\n";

