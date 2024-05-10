<?php

return [
    'fuelPrice' => 59,
    'mileageRate' => 5,
    'autopark' => [
        'bus' => [
            'name' => 'Bus',
            'maxPassengersCount' => 32,
            'maxLuggage' => 1000,
            'fuelConsumption' => 22,
            'maxDistance' => 500,
            'ageIndex' => 9
        ],
        'minivan' => [
            'name' => 'Minivan',
            'maxPassengersCount' => 15,
            'maxLuggage' => 240,
            'fuelConsumption' => 15,
            'maxDistance' => 650,
            'ageIndex' => 2
        ],
        'taxi' => [
            'name' => 'Taxi',
            'maxPassengersCount' => 4,
            'maxLuggage' => 120,
            'fuelConsumption' => 7,
            'maxDistance' => 25,
            'ageIndex' => 10
        ],
        'truck' => [
            'name' => 'Truck',
            'maxPassengersCount' => 2,
            'maxLuggage' => 100,
            'fuelConsumption' => 40,
            'maxDistance' => 1500,
            'ageIndex' => 7
        ],
        'motorcycle' => [
            'name' => 'Motorcycle',
            'maxPassengersCount' => 1,
            'maxLuggage' => 5,
            'fuelConsumption' => 5,
            'maxDistance' => 300,
            'ageIndex' => 3
        ],
        'car' => [
            'name' => 'Car',
            'maxPassengersCount' => 7,
            'maxLuggage' => 300,
            'fuelConsumption' => 8,
            'maxDistance' => 600,
            'ageIndex' => 5
        ],
    ]
];