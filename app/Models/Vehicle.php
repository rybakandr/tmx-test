<?php

namespace app\Models;

class Vehicle
{
    public string $name;

    public int $passengersCount;

    public int $maxLuggageWeight;

    public int $fuelConsumption;

    public int $maxDistance;

    public int $amortisation;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getMaxDistance(): int
    {
        return $this->maxDistance;
    }

    /**
     * @return int
     */
    public function getFuelConsumption(): int
    {
        return $this->fuelConsumption;
    }

    /**
     * @return int
     */
    public function getMaxLuggageWeight(): int
    {
        return $this->maxLuggageWeight;
    }

    /**
     * @return int
     */
    public function getPassengersCount(): int
    {
        return $this->passengersCount;
    }

    /**
     * @return int
     */
    public function getAmortisation(): int
    {
        return $this->amortisation;
    }

    /*
     * Filters element that does not match specified parameters
     */
    public function filter(int $passengers, int $luggage, int $distance)
    {
        $result = $this->getPassengersCount() >= $passengers;
        $result = $result && ($this->getMaxLuggageWeight() >= $luggage);
        $result = $result && ($this->getMaxDistance() >= $distance);

        return $result;
    }

}