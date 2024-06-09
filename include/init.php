<?php

session_start();
include('db.php');
include('queryBuilder.php');
include('page.php');
include('pagination.php');
include('util.php');

$db = new DB([
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'root',
    'db' => 'taiga',
]);