<?php

namespace app\Models;

interface DriverInterface
{
    public function getSalary(int $distance);

    public function getCategory();
}