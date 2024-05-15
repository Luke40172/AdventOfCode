<?php

require ('../../utils/autoload.php');

$analyzer = new Analyzer(env('DEV', false) ? 'example-part2.input' : 'input');

$steps = $analyzer->getSteps2();

echo "Steps: " . $steps . "\n";

