<?php

namespace App\Config;

/**
 * Static class for getting .env constants
 *
 * Class Config
 * @package App\Config
 */
class Config
{
    /**
     * Translate settings in .env to array
     *
     * @var array $configs
     */
    private static $configs = [];

    /**
     * Variable to determine if the static object exists
     *
     * @var bool
     */
    private static $initialized = false;

    /**
     * Initialize static object
     */
    private static function initialize()
    {
        if (self::$initialized)
            return;

        self::$configs = self::importDotEnv();
        self::$initialized = true;
    }

    /**
     * Get the desire config from .env
     *
     * example:
     * Config::get('DB_HOST') outputs 'localhost'
     * Config::get('NON_EXISTING_CONF') outputs false
     *
     * @param string $configName
     * @return bool|mixed
     */
    public static function get(string $configName)
    {
        self::initialize();

        if (isset(self::$configs[$configName])) {
            return self::$configs[$configName];
        } else {
            return false;
        }
    }

    /**
     * Import configurations from .env and return as an array
     *
     * example:
     * self::importDotEnv outputs ['DB_HOST' => 'localhost', ...]
     *
     * @return array
     */
    private static function importDotEnv(): array
    {
        $configs = [];

        $location = $_SERVER['DOCUMENT_ROOT'] . '.env';

        $env = fopen($location, 'r') or die($location);

        while ($line = fgetss($env)) {
            $separator = strpos($line, '=');

            $configName = substr($line, 0, $separator);

            $configValue = rtrim(substr($line, $separator + 1), "\n");

            if (empty($configValue)) {
                continue;
            }

            if (is_numeric($configValue)) {
                $configValue = (int)$configValue;
            }

            $configs[$configName] = $configValue;
        }

        fclose($env);

        return $configs;
    }
}
