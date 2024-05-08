<?php

require ('../../../utils/autoload.php');

new Utils\SplStatic2DArray(5, 5);

$schema = new Schematic('../input');
$sum    = 0;

foreach ($schema->findGearRatios() as $number) {
    echo "Found: " . $number . "\n";
    $sum += $number;
}

echo "Sum: " . $sum . "\n";
