<?php

namespace app\Core;

use app\Helpers\ConfigurationHelper;

class LocalFuelPriceGetter implements FuelPriceInterface
{

    public function __construct(private array $storage)
    {
    }

    public function getPrice($key = 'fuelPrice')
    {
        return (int) ConfigurationHelper::getByKey($key, $this->storage);
    }
}