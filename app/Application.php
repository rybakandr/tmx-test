<?php

namespace app;

use app\Core\FuelPriceInterface;
use app\Core\InputPrompter;
use app\Core\LocalFuelPriceGetter;
use app\Core\VehicleBuilder;
use app\Core\VehicleBuildInterface;
use app\Helpers\ConfigurationHelper;
use app\Models\Driver;
use app\Models\DriverInterface;
use app\Models\Filters\CollectionFilterIterator;

class Application
{
    public \ArrayIterator $collection;

    protected array $filters = [];

    private DriverInterface $driver;

    private FuelPriceInterface $fuelPriceGetter;

    private VehicleBuildInterface $builder;

    public function __construct(private array $configuration)
    {
        $this->collection = new \ArrayIterator();

        $this->builder = new VehicleBuilder();

        $this->initDriver();

        $this->fuelPriceGetter = new LocalFuelPriceGetter($this->getConfiguration());
    }

    /**
     * Runs main interactive process - generate entities, ask for user inputs and do calculations
     */
    public function run()
    {
        $autoparkData = $this->getFromConfig("autopark");
        if(empty($autoparkData))
            throw new \Exception("Configuration error - section \"Autopark\" is missing or empty. ".PHP_EOL." Check config files!",500);

        $this->fillCollection($autoparkData);

        $this->filters['passengers'] = InputPrompter::prompt('Passengers');
        $this->filters['luggage'] = InputPrompter::prompt('Luggage weight');
        $this->filters['distance'] = InputPrompter::prompt('Route length');

        $filteredCollection = new CollectionFilterIterator($this->collection, $this->filters);

        $calcResult = $this->calculateRoutePrice($filteredCollection);

        $this->printResult($calcResult);
    }

    /**
     * Populates new collection with Vehicle objects
     */
    public function fillCollection($data)
    {
        foreach ($data as $vehicle) {
            $model = $this->builder->setData($vehicle)->build();
            $this->collection->append($model);
        }
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    /**
     * @param VehicleBuildInterface $builder
     */
    public function setBuilder(VehicleBuildInterface $builder): void
    {
        $this->builder = $builder;
    }

    /**
     * @param FuelPriceInterface $fuelPriceGetter
     */
    public function setFuelPriceGetter(FuelPriceInterface $fuelPriceGetter): void
    {
        $this->fuelPriceGetter = $fuelPriceGetter;
    }

    /**
     * Init new  driver instance with personal settings
     */
    protected function initDriver()
    {
        $this->driver = new Driver();
        $mileageRate = (int) $this->getFromConfig("mileageRate") ?? 1;
        $this->driver->setKmRate($mileageRate);
    }

    protected function calculateRoutePrice($collection)
    {
        $driverSalary = $this->driver->getSalary($this->filters['distance']);

        $resultSet = [];
        foreach ($collection as $vehicle){

            /* @var $vehicle \app\Models\Vehicle */

            $usedFuel = $this->filters['distance'] * floatval($vehicle->getFuelConsumption() / 100);

            $fuelPrice = $usedFuel * $this->fuelPriceGetter->getPrice();

            $resultSet[] = [
                'vehicleName' => $vehicle->getName(),
                'route_price' => ($fuelPrice * $vehicle->getAmortisation()) + $driverSalary,
            ];
        }

        return $resultSet;
    }

    public function printResult(array $result)
    {
        if (empty($result)){
            echo "There are no results for specified parameters: {$this->filters['passengers']} passengers, {$this->filters['luggage']} kg of luggage, {$this->filters['distance']} km";
        } else {
            echo "----------------------------------------------------------" . PHP_EOL;
            echo "\33[4mResults\33[0m for {$this->filters['passengers']} passengers, {$this->filters['luggage']} kg of luggage and {$this->filters['distance']} km:" . PHP_EOL;
            echo "----------------------------------------------------------" . PHP_EOL;
            foreach ($result as $item){
                echo "\33[36m" . $item['vehicleName'] . "\33[0m will cost \33[32m" . number_format($item['route_price'], 2, '.', ' ') . " UAH\33[0m" . PHP_EOL;
            }
            echo "----------------------------------------------------------" . PHP_EOL;
        }
    }

    private function getFromConfig($key)
    {
        return ConfigurationHelper::getByKey($key, $this->configuration);
    }
}
