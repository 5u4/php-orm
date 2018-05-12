# Migration

 - [Base Types](#base-types)
    - [string](#string)
    - [int](#int)
 - [Constraints](#constraints)
    - [Not Null](#not-null)
    - [PRIMARY KEY](#primary-key)
    - [UNIQUE](#unique-key)
    - [AUTO INCREMENT](#auto-increment)

## Schema

```php
Schema::create(string $tableName, callback $tableFunction [, bool $ifNotExist]): void
```

Create a table.

The `$tableFunction` takes a `Blueprint` input.

### Example

```php
<?php

use Senhung\ORM\Database\Schema;
use Senhung\ORM\Database\Blueprint;

Schema::create('users', function (Blueprint $table) {
    $table->int('id')->primary()->autoIncrement();
    $table->string('username')->unique()->notNull();
    $table->string('email')->unique();
    $table->string('password')->notNull();
});
```

## Base Types

### string

```php
Blueprint::string(string $columnName [, int $columnLength]): Blueprint;
```

Create a `VARCHAR` column with length (default 50).

Example:

```php
<?php

use Senhung\ORM\Database\Blueprint;

/* ... */
$table->string('username', 20);
```

### int

```php
Blueprint::int(string $columnName [, int $columnLength]): Blueprint;
```

Create a `INT` column with length (default 11).

Example:

```php
<?php

use Senhung\ORM\Database\Blueprint;

/* ... */
$table->int('year', 4);
```

## Constraints

### not null

```php
Blueprint::notNull([string|array $columnName]): Blueprint;
```

Make column `NOT NULL`.

Example:

```php
<?php

use Senhung\ORM\Database\Blueprint;

/* ... */
$table->int('year', 4)->notNull();
```

### primary key

```php
Blueprint::primary([string|array $columnName]): Blueprint;
```

Make column becomes `PRIMARY KEY`.

Example:

```php
<?php

use Senhung\ORM\Database\Blueprint;

/* ... */
$table->int('year', 4)->primary();

/* ... */
$table->primary(['compound', 'pk']);
```

### unique key

```php
Blueprint::unique([string|array $columnName]): Blueprint;
```

Make column becomes `UNIQUE`.

Example:

```php
<?php

use Senhung\ORM\Database\Blueprint;

/* ... */
$table->int('year', 4)->unique();

/* ... */
$table->unique(['compound', 'unique_key']);
```

### auto increment

```php
Blueprint::autoIncrement([string|array $columnName]): Blueprint;
```

Make column becomes `AUTO_INCREMENT`.

Example:

```php
<?php

use Senhung\ORM\Database\Blueprint;

/* ... */
$table->int('id')->autoIncrement();
```
