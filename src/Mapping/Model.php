<?php

namespace Senhung\ORM\Mapping;

use Senhung\ORM\Database\QueryBuilder;
use Senhung\ORM\Database\Connection;

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
        if ($primaryKey) {
            $this->find($primaryKey);
        }
    }

    /**
     * Set current object to a row with input id
     *
     * @param string|int $primaryKey
     * @return $this
     */
    public function find($primaryKey): self
    {
        /* Build select query */
        $currentRow = new QueryBuilder();

        /* Select all from database */
        $currentRow->select('*')->from($this->table)->where([$this->primaryKey, '=', $primaryKey]);

        /* Query */
        try {
            $result = Connection::query($currentRow->getQuery());
        } catch (\Exception $e) {
            exit($e->getMessage());
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

        /* Alter current row */
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
        $fields = $this->fillable;
        $attributes = [];
        foreach ($fields as $field) {
            $attributes[$field] = $this->$field;
        }

        return $attributes;
    }

    /**
     * Insert current object into MySQL
     */
    private function insertNewRow(): void
    {
        $attributes = $this->getFillableAttributes();

        $query = new QueryBuilder();
        $query->insertInto($this->table, array_keys($attributes))->values(array_values($attributes));

        try {
            Connection::query($query->getQuery());
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Update current object into MySQL
     */
    private function updateRow(): void
    {
        $primaryKey = $this->primaryKey;

        $attributes = $this->getFillableAttributes();

        $query = new QueryBuilder();
        $query->update($this->table)->set($attributes)->where([$primaryKey, '=', $this->$primaryKey]);

        try {
            Connection::query($query->getQuery());
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}
