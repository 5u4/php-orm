<?php

namespace Senhung\ORM\Database;

use mysqli;
use Senhung\Config\Configuration;

class Connection
{
    /**
     * The database connection
     *
     * @var mysqli $database
     */
    private static $database;

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

        self::$database = self::connect();
        self::$initialized = true;
    }

    /**
     * Connecting to MySQL
     *
     * @return mysqli
     */
    private static function connect(): mysqli
    {
        /* Set config path */
        Configuration::setPath('.env');

        /* Initialize config */
        Configuration::initializeConfigs();

        $database = new mysqli(
            Configuration::read('DB_HOST'),
            Configuration::read('DB_USERNAME'),
            Configuration::read('DB_PASSWORD'),
            Configuration::read('DB_DATABASE'),
            Configuration::read('DB_PORT')
        );

        if ($database->connect_errno) {
            die("Database connection error (" . $database->connect_errno . "): " . $database->connect_error . "\n");
        }

        return $database;
    }

    /**
     * @param string $query
     * @return bool|\mysqli_result
     * @throws \Exception
     */
    public static function query(string $query)
    {
        self::initialize();

        $result = self::$database->query($query);

        if (!$result) {
            throw new \Exception(mysqli_error(self::$database) . "\n" . $query . "\n\n");
        }

        return $result;
    }
}
