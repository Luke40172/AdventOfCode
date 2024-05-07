<?php

require ('DigitMatcher.php');

$matcher = new DigitMatcher();

$handle = fopen('input', 'r');
if (!$handle) {
    die('Unable to load puzzle input');
}

echo "Start\n";

$sum = 0;
while ($line = fgets($handle)) {
    $numbers = $matcher->extractDigits(trim($line));


    $firstDigit = current($numbers);
    $lastDigit  = end($numbers);
    $sum += intval($firstDigit . $lastDigit);

    printf(
        "%d - %s - %s - %s\n",
        intval($firstDigit . $lastDigit),
        implode(',', $numbers),
        $matcher->latestSentence
    );
}

echo $sum . "\n";
