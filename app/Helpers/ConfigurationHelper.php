<?php

namespace app\Helpers;

class ConfigurationHelper
{
    public static function getByKey($key, $data)
    {
        return isset($key, $data) ? $data[$key] : null;
    }
}