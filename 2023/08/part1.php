<?php

require ('../../utils/autoload.php');

$analyzer = new Analyzer(env('DEV', false) ? 'example.input' : 'input');

$steps = $analyzer->getSteps();

echo "Steps: " . $steps . "\n";

