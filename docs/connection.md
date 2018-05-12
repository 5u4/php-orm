# Connection

## Query

```php
Connection::query(string $query): mysqli_result
```

### Example

```php
<?php

use Senhung\ORM\Database\Connection;

$query = 
"CREATE TABLE `users` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(20)
);";

$result = Connection::query($query);

print_r($result->fetch_assoc());
```
