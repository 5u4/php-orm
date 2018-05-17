<?php

namespace Senhung\ORM\Relations;

use Senhung\ORM\Mapping\Model;

class Relation
{
    /** @var Model $localInstance */
    private $localInstance;
    /** @var string $foreignClassName */
    private $foreignClassName;
    /** @var string $foreignKey */
    private $foreignKey;
    /** @var string $localKey */
    private $localKey;

    /**
     * Relation constructor.
     * @param Model $localInstance
     * @param string $foreignClassName
     * @param string $foreignKey
     * @param string $localKey
     */
    public function __construct(Model $localInstance, string $foreignClassName, string $foreignKey, string $localKey)
    {
        $this->localInstance = $localInstance;
        $this->foreignClassName = $foreignClassName;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    /**
     * Relate a model to current model
     *
     * @param Model $model
     */
    public function attach(Model $model): void
    {
        /* Check if the class matches */
        if (get_class($model) != $this->foreignClassName) {
            return;
        }

        /* Set relationship */
        $this->localInstance->{$this->localKey} = $model->{$this->foreignKey};

        /* Store into database */
        $this->localInstance->save();
    }
}
