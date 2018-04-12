<?php

namespace App\Database;

use App\Database\Database;
use App\Database\Blueprint;

class Schema
{
    /**
     * Create Table
     *
     * Example:
     * Schema::create('test', function (Blueprint $table) {
     *     $table->string('name')->notNull()->primary();
     *     $table->int('no_constraint');
     *     $table->string('some_how_related_to_no_constraint');
     *
     *     $table->unique(['no_constraint', 'some_how_related_to_no_constraint']);
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
