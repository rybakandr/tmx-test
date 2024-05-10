<?php

require_once 'vendor/autoload.php';
try {
    $config = require __DIR__ . '/config/app.config.php';
    // Different types of instances can be set before run method (e.g. fuelPriceGetters, Builders etc.)
    (new \app\Application($config))->run();
} catch (Exception $exception){
    print_r("\033[31m Error\033[0m {$exception->getCode()}: " . $exception->getMessage());
}
