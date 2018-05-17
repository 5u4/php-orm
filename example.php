<?php

require_once 'vendor/autoload.php';
require_once 'src/Mapping/Model.php';
require_once 'src/Relations/Relation.php';

/* -------------------------------------------------
 * Database Connection
 * -------------------------------------------------
 */

use Senhung\DB\Database\Connection;

$db = new Connection();

/* -------------------------------------------------
 * Schema Create Table
 * -------------------------------------------------
 */

use Senhung\DB\Schema\Schema;
use Senhung\DB\Schema\Blueprint;

Schema::drop('users');

Schema::drop('passports');

Schema::create('passports', function (Blueprint $table) {
    $table->int('id')->primary()->autoIncrement();
    $table->string('country')->notNull();
});

Schema::create('users', function (Blueprint $table) {
    $table->int('id')->primary()->autoIncrement();
    $table->string('username')->unique()->notNull();
    $table->string('email')->unique()->notNull();
    $table->string('password')->notNull();
    $table->int('passport_id');

    $table->foreign('passport_id')->references('passports', 'id')
        ->onDelete(Blueprint::CASCADE)->onUpdate(Blueprint::CASCADE);
});

/* -------------------------------------------------
 * Model
 * -------------------------------------------------
 */

use Senhung\ORM\Mapping\Model;

/**
 * Class User
 * @property string $username
 * @property string $email
 * @property string $password
 */
class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'email', 'password', 'passport_id'];

    public function passport()
    {
        return $this->hasOne(Passport::class, 'id', 'passport_id');
    }
}

/**
 * Class Passport
 * @property string $country
 */
class Passport extends Model
{
    protected $table = 'passports';
    protected $primaryKey = 'id';
    protected $fillable = ['country'];
}

/* -------------------------------------------------
 * Model Create
 * -------------------------------------------------
 */

$passport = new Passport();
$passport->country = 'Canada';
$passport->save();

$user = new User();
$user->username = 'user';
$user->email = 'email@email.com';
$user->password = 'password';
$user->save();

$user->passport()->attach($passport);

/* -------------------------------------------------
 * Model Find
 * -------------------------------------------------
 */

$user = new User(1);

print "User name: " . $user->username . "\n";

/* -------------------------------------------------
 * Model Update
 * -------------------------------------------------
 */

$user->username = 'changed';
$user->save();

print "Changed username: " . $user->username . "\n";
