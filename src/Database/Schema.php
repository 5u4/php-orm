<?php

namespace Senhung\ORM\Database;

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
     * }, true);
     *
     * @param string $tableName
     * @param callable $function
     * @param bool $ifNotExists
     * @return void
     */
    public static function create(string $tableName, callable $function, bool $ifNotExists = true): void
    {
        /* Get Table Columns + Constraints */
        $table = new Blueprint();

        call_user_func($function, $table);

        /* Build Query */
        $query = new QueryBuilder();

        $query->createTable(
            $tableName,
            $table->getColumns(),
            empty($table->getConstraints()) ? null : $table->getConstraints(),
            $ifNotExists
        );

        /* Create Table */
        try {
            Connection::query($query->getQuery());
        }

        /* Error */
        catch (\Exception $exception) {
            die($exception->getMessage());
        }
    }
}
