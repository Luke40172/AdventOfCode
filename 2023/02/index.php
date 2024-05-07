<?php

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

    $cubeInfo = [
        'red' => 0,
        'green' => 0,
        'blue' => 0,
    ];

    foreach ($setData as $setString) {
        $handData = explode(';', $setString);
        foreach ($handData as $handString) {
            $cubeData = explode(',', $handString);
            foreach ($cubeData as $cubeString) {
                list($amount, $color) = explode(' ', trim($cubeString));
                // if ($amount > $cubeInfo[$color]) {
                //     $possible = false;
                //     break 2;
                // }
                if ($cubeInfo[$color] < $amount) {
                    $cubeInfo[$color] = $amount;
                }
            }
        }
    }

    $value = array_reduce(
        $cubeInfo,
        static fn($carry, $amount) => $carry * $amount,
        1
    );
    var_dump($value);
    $sum += $value;
}

echo $sum . "\n";
