<?php

namespace app;

use app\Core\InputPrompter;
use app\Core\VehicleBuilder;
use app\Core\VehicleBuildInterface;
use app\Models\Driver;
use app\Models\DriverInterface;
use app\Models\Filters\CollectionFilterIterator;
use Wruczek\PhpFileCache\PhpFileCache;

class Application
{
    public \ArrayIterator $collection;

    protected array $filters = [];

    private DriverInterface $driver;

    private VehicleBuildInterface $builder;


    public function __construct(private array $configuration, private PhpFileCache $cache)
    {
        $this->cache->clearCache();
        $this->setCachedData();
    }

    public function run()
    {
        $this->init();

        $autoparkData = $this->cache->retrieve("app.config.autopark");
        $this->fillCollection($autoparkData);

        $this->filters['passengers'] = InputPrompter::prompt('Passengers');
        $this->filters['luggage'] = InputPrompter::prompt('Luggage weight');
        $this->filters['distance'] = InputPrompter::prompt('Route length');

        $filteredCollection = new CollectionFilterIterator($this->collection, $this->filters);

        $calcResult = $this->calculateRoutePrice($filteredCollection);

        $this->printResult($calcResult);
    }

    /*
     * Populates new collection with Vehicle objects
     */
    public function fillCollection($data)
    {
        foreach ($data as $vehicle) {
            $model = $this->builder->setData($vehicle)->build();
            $this->collection->append($model);
        }
    }

    /*
     * Initializes entities and components
     */
    protected function init()
    {
        $this->initDriver();
        $this->initCollection();
        $this->initBuilder();
    }

    /*
     * Builder for creating vehicles in autopark
     */
    protected function initBuilder()
    {
        $this->builder = new VehicleBuilder();
    }

    /*
     * New collection of vehicles
     */
    protected function initCollection()
    {
        $this->collection = new \ArrayIterator();
    }

    /*
     * Get fuel price from cache (config)
     */
    protected function getFuelPrice(): int
    {
        return (int)$this->cache->retrieve("app.config.fuelPrice");
    }

    /*
     * Init new  driver instance with personal settings
     */
    protected function initDriver()
    {
        $this->driver = new Driver();
        $rate = $this->cache->retrieve("app.config.mileageRate");
        $this->driver->setKmRate($rate);
    }

    /*
     * Writes auto park data to filecache
     */
    protected function setCachedData()
    {
        foreach ($this->configuration as $keyValue => $configValue) {
            $cacheKey = "app.config." . $keyValue;
            if ($this->cache->isExpired($cacheKey, true)) {
                $this->cache->store($cacheKey, $configValue, 86400);
            }
        }
    }

    protected function calculateRoutePrice($collection)
    {
        $driverSalary = $this->driver->getSalary($this->filters['distance']);

        $resultSet = [];
        foreach ($collection as $vehicle){

            /* @var $vehicle \app\Models\Vehicle */

            $usedFuel = $this->filters['distance'] * floatval($vehicle->getFuelConsumption() / 100);

            $fuelPrice = $usedFuel * $this->getFuelPrice();

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
}
