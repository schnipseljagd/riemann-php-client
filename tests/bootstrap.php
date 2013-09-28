<?php

error_reporting(-1);

$autoload = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoload)) {
    echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~" . PHP_EOL;
    echo " You need to execute `composer install` before running the tests. " . PHP_EOL;
    echo " Vendors are required for complete test execution. " . PHP_EOL;
    echo "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~" . PHP_EOL . PHP_EOL;
    exit(1);
}

$loader = require $autoload;
$loader->add('Riemann\\Test', __DIR__);