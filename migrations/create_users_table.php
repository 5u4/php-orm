<?php

use App\Database\Blueprint;
use App\Database\Schema;

try {
    Schema::create('users', function (Blueprint $table) {
        $table->int('id')->primary();
        $table->string('username')->unique()->notNull();
        $table->string('email')->unique();
        $table->string('password')->notNull();

        return $table;
    });
} catch (Exception $e) {
    echo $e->getMessage();
}
