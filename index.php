<?php

require_once 'vendor/autoload.php';

try {
    $config = require __DIR__ . '/config/app.config.php';

    $app = new \app\Application($config);

    // Different types of instances can be set before run method (e.g. fuelPriceGetters, Builders etc.)
    // $app->setFuelPriceGetter(new  \app\Core\RemoteFuelPriceGetter());

    $app->run();
} catch (Exception $exception){
    print_r("\033[31m Error\033[0m {$exception->getCode()}: " . $exception->getMessage());
}
