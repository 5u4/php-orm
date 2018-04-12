<?php

namespace App\ORM;

include './app/Config/Config.php';

use mysqli;
use App\Config\Config;

class Database
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
     * Create table with columns, constraints, ...
     *
     * example:
     *
     * Database::createTable(['name' => 'INT NOT NULL'], ['PRIMARY KEY' => 'name'])
     * creates table with query:
     * CREATE TABLE IF NOT EXISTS ('name' INT NOT NULL, PRIMARY KEY (name));
     *
     * @param string $tableName
     * @param array $columns
     * @param array|null $constraints
     * @param bool $ifNotExists
     * @throws \Exception
     */
    public static function createTable(string $tableName, array $columns, array $constraints = null, bool $ifNotExists = true)
    {
        self::initialize();

        $query = 'CREATE TABLE ';

        if ($ifNotExists) {
            $query .= 'IF NOT EXISTS ';
        }

        $query .= $tableName . ' ';

        $query .= '(' . self::formater($columns, ', ', "`%s` %s");

        if (isset($constraints)) {
            $query .= ', ' . self::formater($constraints, ', ', '%s (`%s`)');
        }

        $query .= ');';

        self::databaseQuery($query);
    }

    /**
     * Connecting to MySQL
     *
     * @return mysqli
     */
    private static function connect(): mysqli
    {
        $database = new mysqli(
            Config::get('DB_HOST'),
            Config::get('DB_USERNAME'),
            Config::get('DB_PASSWORD'),
            Config::get('DB_DATABASE'),
            Config::get('DB_PORT')
        );

        if ($database->connect_errno) {
            die("Database connection error (" . $database->connect_errno . "): " . $database->connect_error . "\n");
        }

        return $database;
    }

    /**
     * Format array to a desired formatted string
     *
     * @param array $columns
     * @param string $glueBetweenElements
     * @param string $format
     * @return string
     */
    private static function formater(array $columns, string $glueBetweenElements, string $format): string
    {
        return implode($glueBetweenElements, array_map(
            function ($v, $k) use ($format) { return sprintf($format, $k, $v); },
            $columns,
            array_keys($columns)
        ));
    }

    /**
     * @param string $query
     * @throws \Exception
     */
    private static function databaseQuery(string $query)
    {
        $result = self::$database->query($query);

        if (!$result) {
            throw new \Exception(mysqli_error(self::$database) . "\n" . $query . "\n\n");
        }
    }
}


try {
    Database::createTable('test', ['name' => 'INT NOT NULL'], ['PRIMARY KEY' => 'name']);
} catch (\Exception $e) {
    echo $e->getMessage();
}
