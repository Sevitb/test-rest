<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Src\Database\Database;

$db = new Database;



$db->preparedQuery('CREATE DATABASE IF NOT EXISTS db_test_rest;');