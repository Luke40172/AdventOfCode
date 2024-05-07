<?php

require ('Schematic.php');

$schema = new Schematic('input');
$sum    = 0;

foreach ($schema->findNumbers() as $number) {
    echo "Found: " . $number . "\n";
    $sum += $number;
}

echo "Sum: " . $sum . "\n";
