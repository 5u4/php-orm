<?php

namespace App\ORM;

include './app/ORM/Database.php';
include './app/ORM/Blueprint.php';

use App\ORM\Database;
use App\ORM\Blueprint;

class Schema
{
    /**
     * Create Table
     *
     * Example:
     * Schema::create('test', function (Blueprint $table) {
     *     $table->string('name')->notNull()->primary();
     *     $table->int('on_constraint');
     *     $table->string('some_how_related_to_no_constraint');
     *
     *     $table->unique(['on_constraint', 'some_how_related_to_no_constraint']);
     *
     *     return $table;
     * }, true);
     *
     * @param string $tableName
     * @param callable $function
     * @param bool $ifNotExists
     * @throws \Exception
     */
    public static function create(string $tableName, callable $function, bool $ifNotExists = false)
    {
        $table = call_user_func($function, new Blueprint());

        Database::createTable(
            $tableName,
            $table->getColumns(),
            empty($table->getConstraints()) ? null : $table->getConstraints(),
            $ifNotExists
        );
    }
}
