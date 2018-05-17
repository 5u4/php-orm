<?php

namespace Senhung\ORM\Mapping;

use Senhung\DB\Database\QueryBuilder;
use Senhung\DB\Database\Connection;

class Model
{
    /**
     * Check if the object is a new row or existing row
     *
     * @var bool $isNew
     */
    private $isNew = true;

    /**
     * The table name
     *
     * @var string|null $table
     */
    protected $table = null;

    /**
     * The database name
     *
     * @var string $database
     */
    protected $database = 'DB';

    /**
     * Database connection
     *
     * @var Connection $connection
     */
    private $connection;

    /**
     * The primary key of the table
     *
     * @var array|string|null $primaryKey
     */
    protected $primaryKey = null;

    /**
     * All the fillable attributes in the table
     *
     * @var array $fillable;
     */
    protected $fillable = [];

    /**
     * Model constructor.
     * @param string|int|null $primaryKey
     */
    public function __construct($primaryKey = null)
    {
        $this->connection = new Connection($this->database);

        if ($primaryKey) {
            $this->find($primaryKey);
        }
    }

    /**
     * Set current object to a row with input id
     *
     * @param string|int $primaryKey
     * @return $this
     * @throws \Exception
     */
    public function find($primaryKey): self
    {
        /* Build select query */
        $currentRow = new QueryBuilder();

        /* Select all from database */
        $currentRow->select('*')->from($this->table)->where([$this->primaryKey, '=', $primaryKey]);

        /* Query */
        $result = $this->connection->query($currentRow);

        /* If the user is not found */
        if ($result->num_rows <= 0) {
            $message = get_class($this) . " with " . $this->primaryKey . " " . $primaryKey . " is not found\n";
            throw new \Exception($message);
        }

        /* Convert the results to model attributes */
        $this->contentToModel($result->fetch_assoc());

        /* Set Found Object */
        $this->isNew = false;

        return $this;
    }

    /**
     * Set array content to this object
     *
     * @param array $results
     */
    private function contentToModel(array $results): void
    {
        foreach ($results as $key => $column) {
            /* Assign the result to current model's attributes */
            $this->$key = $column;
        }
    }

    /**
     * Save current model to MySQL
     */
    public function save(): void
    {
        /* If current object is a new object, create */
        if ($this->isNew) {
            $this->insertNewRow();
        }

        /* Else update current row */
        else {
            $this->updateRow();
        }
    }

    /**
     * Get current object fillable attributes
     *
     * @return array
     */
    private function getFillableAttributes(): array
    {
        $attributes = [];

        /* Go through fillable array */
        foreach ($this->fillable as $field) {
            /* Check if current model has the attribute */
            if (isset($this->$field)) {
                /* Push model field to attribute array */
                $attributes[$field] = $this->$field;
            }
        }

        return $attributes;
    }

    /**
     * Insert current object into MySQL
     */
    private function insertNewRow(): void
    {
        /* Get all fillable attributes */
        $attributes = $this->getFillableAttributes();

        /* Build insert query */
        $query = new QueryBuilder();

        $query->insertInto($this->table, array_keys($attributes))->values(array_values($attributes));

        /* Query */
        $this->connection->query($query);

        /* Set current instance to not new */
        $this->isNew = false;
    }

    /**
     * Update current object into MySQL
     */
    private function updateRow(): void
    {
        /* Get all fillable attributes */
        $attributes = $this->getFillableAttributes();

        /* Build update query */
        $query = new QueryBuilder();

        $query->update($this->table)->set($attributes)->where([$this->primaryKey, '=', $this->{$this->primaryKey}]);

        /* Query */
        $this->connection->query($query);
    }
}
