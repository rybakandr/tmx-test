<?php

namespace app\Core;

use app\Models\Vehicle;

class VehicleBuilder implements VehicleBuildInterface
{
    private $dataArray = [];

    private Vehicle $model;

    public function initModel()
    {
        $this->model = new Vehicle();
    }

    /**
     * @param array $dataArray
     */
    public function setData(array $data): self
    {
        $this->dataArray = $data;
        return $this;
    }

    public function setName(): self
    {
        $this->model->name = $this->dataArray['name'];
        return $this;
    }

    public function setPassengersLimit(): self
    {
        $this->model->passengersCount = $this->dataArray['maxPassengersCount'];
        return $this;
    }

    public function setLuggageLimit(): self
    {
        $this->model->maxLuggageWeight = $this->dataArray['maxLuggage'];
        return $this;
    }

    public function setFuelConsumption(): self
    {
        $this->model->fuelConsumption = $this->dataArray['fuelConsumption'];
        return $this;
    }

    public function setDistanceLimit(): self
    {
        $this->model->maxDistance = $this->dataArray['maxDistance'];
        return $this;
    }

    public function setAgeIndex(): self
    {
        $this->model->amortisation = $this->dataArray['ageIndex'];
        return $this;
    }

    public function build(): Vehicle
    {
        $this->initModel();
        $this
            ->setName()
            ->setPassengersLimit()
            ->setLuggageLimit()
            ->setFuelConsumption()
            ->setDistanceLimit()
            ->setAgeIndex();

        return $this->model;
    }
}