<?php

namespace Senhung\ORM\Database;

class QueryBuilder
{
    /** @var string $query */
    private $query = '';

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Create table with columns, constraints, ...
     *
     * example:
     *
     * Database::createTable('test', ['name' => ['INT', 'NOT NULL']], ['PRIMARY KEY' => 'name'])
     * creates table with query:
     * CREATE TABLE IF NOT EXISTS test (`name` INT NOT NULL, PRIMARY KEY (`name`));
     *
     * @param string $tableName
     * @param array $columns
     * @param array|null $constraints
     * @param bool $ifNotExists
     * @return void
     */
    public function createTable(string $tableName, array $columns, array $constraints = null, bool $ifNotExists = true): void
    {
        $this->query = 'CREATE TABLE ';

        if ($ifNotExists) {
            $this->query .= 'IF NOT EXISTS ';
        }

        $this->query .= '`' . $tableName . '` ';

        $this->query .= '(' . self::formatter($columns, "`%s` %s", ', ', ' ');

        if (isset($constraints)) {
            $this->query .= ', ' . self::formatter($constraints, '%s (%s)', ', ');
        }

        $this->query .= ');';
    }

    /**
     * Format array to a desired formatted string
     *
     * example:
     * Database::formatter(['name' => ['INT', 'NOT NULL']], "`%s` %s", ', ', ' ')
     * outputs
     * `name` INT NOT NULL
     *
     * @param array $items
     * @param string $format
     * @param string $glueBetweenElements
     * @param string|null $glueBetweenItems
     * @return string
     */
    private static function formatter(array $items, string $format, string $glueBetweenElements, string $glueBetweenItems = null): string
    {
        $queryItems = '';

        foreach ($items as $key => $value) {
            if (is_array($value)) {
                $value = implode(
                    isset($glueBetweenItems) ? $glueBetweenItems : $glueBetweenElements,
                    $value
                );
            }

            $queryItems .= sprintf($format, $key, $value) . $glueBetweenElements;
        }

        return rtrim($queryItems, $glueBetweenElements);
    }
}
