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
     * QueryBuilder::createTable('test', ['name' => ['INT', 'NOT NULL']], ['PRIMARY KEY' => 'name'], true)
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
     * SELECT ...
     *
     * @param array|string $columns
     * @return QueryBuilder
     */
    public function select($columns): QueryBuilder
    {
        $this->query .= 'SELECT ';

        if (gettype($columns) == 'array') {
            $this->query .= implode(", ", $columns) . ' ';
        } else {
            $this->query .= $columns . ' ';
        }

        return $this;
    }

    /**
     * FROM ...
     *
     * @param string $table
     * @return QueryBuilder
     */
    public function from(string $table): QueryBuilder
    {
        $this->query .= 'FROM ' . $table . ' ';

        return $this;
    }

    /**
     * WHERE ...
     *
     * @param array $conditions
     * @return QueryBuilder
     */
    public function where(array $conditions): QueryBuilder
    {
        $this->query .= 'WHERE ';

        /* One Condition: ['name', '=', 'alex'] */
        if (gettype($conditions[0]) != 'array') {
            $this->query .= '`' . $conditions[0] . '` ' . $conditions[1] . ' ' . $conditions[2] . ' ';
        }

        /* Multiple Conditions: [
            ['name', '=', 'alex'],
            ['age', '>', '20']
        ] */
        else {
            $cons = [];
            foreach ($conditions as $condition) {
                $cons[] = implode(" ", $condition);
            }
            $this->query .= implode(" AND ", $cons) . ' ';
        }

        return $this;
    }

    /**
     * INSERT INTO ...
     *
     * @param string $table
     * @param array $fields
     * @return QueryBuilder
     */
    public function insertInto(string $table, array $fields): QueryBuilder
    {
        $this->query = 'INSERT INTO `' . $table . '` (`' . implode('`, `', $fields) . '`) ';

        return $this;
    }

    /**
     * VALUES ...
     *
     * @param array $values
     * @return QueryBuilder
     */
    public function values(array $values): QueryBuilder
    {
        $this->query .= "VALUES ('" . implode("', '", $values) . "') ";

        return $this;
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
