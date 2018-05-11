<?php

namespace Senhung\ORM\Mapping;

use Senhung\ORM\Database\QueryBuilder;
use Senhung\ORM\Database\Connection;

class Model
{
    private $isNew = true;

    protected $table = null;

    public $primaryKey = null;

    public $fillable = [];

    /**
     * Set current object to a row with input id
     *
     * @param int $id
     * @return $this
     */
    public function find(int $id): self
    {
        /* Build select query */
        $currentRow = new QueryBuilder();

        /* Select all from database */
        $currentRow->select('*')->from($this->table)->where([$this->primaryKey, '=', $id]);

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

    public function save()
    {
        /* If current object is a new object, create */
        if ($this->isNew) {
            $this->insertNewRow();
        }

        /* Alter current row */
        else {

        }
    }

    /**
     * Insert current object into MySQL
     */
    private function insertNewRow(): void
    {
        $fields = $this->fillable;
        $values = [];
        foreach ($fields as $field) {
            $values[] = $this->$field;
        }

        $query = new QueryBuilder();
        $query->insertInto($this->table, $fields)->values($values);

        try {
            Connection::query($query->getQuery());
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}
