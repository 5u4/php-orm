<?php

namespace App\Model;

use App\Database\Database;

class Model
{
    protected $table = null;

    public $primaryKey = null;

    public function __construct()
    {

    }

    public function save()
    {
        $attributes = get_object_vars($this);

        unset($attributes['primaryKey']);
        unset($attributes['table']);


    }
}

class User extends Model
{
    protected $table = 'users';

//    public $primaryKey = 'id';
}

$user = new User();

$user->username = 'test';
$user->email = 'example@example.com';
$user->password = 'password';

$user->save();

print_r(get_object_vars($user));
