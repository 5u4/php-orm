# Model

## Example Model

```php
<?php

use Senhung\ORM\Mapping\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'email', 'password'];
}
```

## Methods

### find

```php
Model::find(int|string $primaryKey): void;
```

Set the current object to the row that has the same primary key as input

Example:

```php
<?php

use User;

$user = new User();

/* Find user has id of 1 */
$user->find(1);
```
