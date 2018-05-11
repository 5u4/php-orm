<?php

namespace Senhung\ORM\Mapping;

use Senhung\ORM\Database\QueryBuilder;
use Senhung\ORM\Database\Connection;

class Model
{
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

    }

//    /**
//     * Get all model attributes
//     *
//     * @return array
//     */
//    private function getAttributes(): array
//    {
//        $attributes = get_object_vars($this);
//
//        foreach (self::VARIABLES_OTHER_THAN_ATTRIBUTES as $variable) {
//            unset($attributes[$variable]);
//        }
//
//        return $attributes;
//    }
}
