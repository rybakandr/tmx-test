<?php

use Wruczek\PhpFileCache\PhpFileCache;

require_once 'vendor/autoload.php';

try {
    $config = require __DIR__ . '/config/app.config.php';
    $cache = new PhpFileCache();

    (new \app\Application($config, $cache))->run();
} catch (Exception $exception){
    print_r("\033[31m Error\033[0m {$exception->getCode()}: " . $exception->getMessage());
}
