<?php

namespace app\Helpers;

class ConfigurationHelper
{
    public static function getByKey($key, $data)
    {
        return isset($data[$key]) ? $data[$key] : null;
    }
}