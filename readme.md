# PHP MySQL ORM

## Description

This is an simple object relational mapping for MySQL written in PHP7.
It tries to imitate the ORM, [Eloquent](https://laravel.com/docs/5.6/eloquent), used by Laravel.

## Syntax

### Create Table

```
Schema::create('test', function (Blueprint $table) {
    $table->string('name')->notNull()->primary();
    $table->int('on_constraint');
    $table->string('some_how_related_to_no_constraint');
    
    $table->unique(['on_constraint', 'some_how_related_to_no_constraint']);
    
    return $table;
}, true);
```
