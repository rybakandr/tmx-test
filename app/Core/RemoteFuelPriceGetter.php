<?php

namespace app\Core;

class RemoteFuelPriceGetter implements FuelPriceInterface
{

    public function getPrice()
    {
        // Immitation of some another way for getting fuel price
        return rand(52, 57);
    }
}