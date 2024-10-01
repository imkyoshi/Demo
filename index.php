<?php
session_start();

require 'vendor/autoload.php';
require_once 'app/config/Connection.php';

use app\controller\AuthController1;
use app\model\AuthDAL;
use app\config\Connection;
use app\Helpers\Cookies;


$connection = new Connection();
$pdo = $connection->connect();

//Fetch the Controller,Model And Helpers
$authDAL = new AuthDAL($pdo);
$cookies = new Cookies();
$authController = new AuthController1($authDAL, $cookies);


// routes login, register