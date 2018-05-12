# PHP MySQL ORM

## Description

This is a simple object relational mapping for MySQL written in PHP7.
It tries to imitate the ORM, [Eloquent](https://laravel.com/docs/5.6/eloquent), used by Laravel.

## Setup

1. Install

```bash
$ composer require senhung/php-mysql-orm
```

2. Config

Create a `.env` file and fill out the settings using the template [.env.example](.env.example)

## Documents

### Main

The main function of this ORM

 - [Migration](docs/migration.md)
 - [Model](docs/model.md)

### Helpers

Some other helper classes you can use

 - [Connection](docs/connection.md)
 - [QueryBuilder](docs/querybuilder.md)
