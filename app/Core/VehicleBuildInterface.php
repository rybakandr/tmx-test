<?php

namespace app\Core;

interface VehicleBuildInterface
{
    public function initModel();

    public function setData(array $data);

    public function setName();

    public function setPassengersLimit();

    public function setLuggageLimit();

    public function setFuelConsumption();

    public function setDistanceLimit();

    public function setAgeIndex();

    public function build();
}