# PHP MySQL ORM

## Description

This is an simple object relational mapping for MySQL written in PHP7.
It tries to imitate the ORM, [Eloquent](https://laravel.com/docs/5.6/eloquent), used by Laravel.

## Example Syntax

### Create Table

```
Schema::create('test', function (Blueprint $table) {
    /* Create a column named `name` which cannot be null and is primary key */
    $table->string('name')->notNull()->primary(); 
    
    /* Create a column named `no_constraint` which has no constraint for now */
    $table->int('no_constraint');
    
    /* Create a column name `some_how_related_to_no_constraint` */
    $table->string('some_how_related_to_no_constraint');
    
    /* Make the combination of `no_constraint` and `some_how_related_to_no_constraint` unique */
    $table->unique(['no_constraint', 'some_how_related_to_no_constraint']);
    
    return $table;
}, true);
```
