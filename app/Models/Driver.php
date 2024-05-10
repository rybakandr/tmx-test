<?php

namespace app\Models;

class Driver implements DriverInterface
{
    public int $kmRate;


    public function getSalary(int $distance)
    {
        return $this->getCategory() * $this->getKmRate() * $distance;
    }

    /*
     * Calculates driver's category coefficient
     * Enums may be implemented for example or even some new instance (e.g. RateSetterClass)
     * @return int
     */
    public function getCategory(): int
    {
        return rand(2, 5);
    }

    /*
     * Gets kilometrage rate for driver
     * @param bool $ownRate if true you may implement your own logic for calculating every single driver's KM rate
     * @return int
     */
    public function getKmRate(bool $ownRate = false): int
    {
        return $ownRate ? $this->calculateOwnRate() : $this->kmRate;
    }

    public function setKmRate(int $kmRate): void
    {
        $this->kmRate = $kmRate;
    }

    public function calculateOwnRate()
    {
        // your logic...
        return 1;
    }
}