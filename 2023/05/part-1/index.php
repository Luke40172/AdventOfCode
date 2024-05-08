<?php

require ('../../../utils/autoload.php');

$almanac = new Almanac(env('DEV', false) ? '../example.input' : '../input');
$lowest  = PHP_INT_MAX;

foreach ($almanac->analyzeSeeds() as $location) {
    echo "Found: " . $location . "\n";
    $lowest = min($lowest, $location);
    // exit;
}

echo "Lowest: " . $lowest . "\n";
