# Query Builder

## Create Table

```php
QueryBuilder::createTable(string $tableName, array $fields, array $constraints [, bool $ifNotExists]): void
```

Generate a create table query.

Example:

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

$query->createTable('users', ['name' => ['INT', 'NOT NULL']], ['PRIMARY KEY' => 'name']);

/* CREATE TABLE IF NOT EXISTS users (`name` INT NOT NULL, PRIMARY KEY (`name`)); */
print $query->getQuery();
```

## Foreign Key Constraint

Example:

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

$query->foreign('users', 'passport_id')->references('passport', 'id')->onDelete(QueryBuilder::CASCADE)->onUpdate(QueryBuilder::CASCADE);

/* FOREIGN KEY users (passport_id) REFERENCES passport (id) ON DELETE CASCADE ON UPDATE CASCADE */
print $query->getQuery();
```


## Select

Example:

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

$query->select('*')->from('users')->where(['name', '=', 'alex']);

/* SELECT * FROM users WHERE `name` = alex */
print $query->getQuery();
```

## Insert

Example:

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

$query->insertInto('users', ['name'])->values(['alex']);

/* INSERT INTO `users` (`name`) VALUES ('alex') */
print $query->getQuery();
```

Or you can do it like:

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

$query->insertInto('users', ['name'])->select(['alex'])->select(['senhung']);

/* INSERT INTO `users` (`name`) SELECT alex SELECT senhung */
print $query->getQuery();
```

## Update

Example:

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

$query->update('users')->set(['name' => 'senhung'])->where(['name', '=', 'alex']);

/* UPDATE users SET name='senhung' WHERE `name` = alex */
print $query->getQuery();
```

## Clear

```php
<?php

use Senhung\ORM\Database\QueryBuilder;

$query = new QueryBuilder();

/* ... */

/* Set query to empty */
$query->clear();
```
