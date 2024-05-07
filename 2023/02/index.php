<?php

$cubeInfo = [
    'red' => 12,
    'green' => 13,
    'blue' => 14,
];

$handle = fopen('input', 'r');
if (!$handle) {
    die('Unable to load puzzle input');
}

echo "Start\n";

$sum = 0;
while ($line = fgets($handle)) {
    list($gameString, $data) = explode(': ', $line);
    list(, $gameId)          = explode(' ', $gameString);
    $setData                 = explode(';', $data);
    $possible                = true;
    foreach ($setData as $setString) {
        $handData = explode(';', $setString);
        foreach ($handData as $handString) {
            $cubeData = explode(',', $handString);
            foreach ($cubeData as $cubeString) {
                list($amount, $color) = explode(' ', trim($cubeString));
                if ($amount > $cubeInfo[$color]) {
                    $possible = false;
                    break 2;
                }
            }
        }
    }

    if ($possible) {
        $sum += $gameId;
    }
}

echo $sum . "\n";
