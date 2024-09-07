<?php

// Autoload necessary classes (you can adjust this based on your autoload setup)
require 'app/config/Connection.php';
require 'app/controller/AuthController.php';
require 'app/model/AuthDAL.php';
require 'app/Helpers/Cookies.php';

// Parse URL
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$path = $request_uri[0];

// Set up dependencies
use app\controller\AuthController;
use app\model\AuthDAL;
use app\config\Connection;

$connection = new Connection();
$pdo = $connection->connect();
$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

// Routing logic
switch ($path) {
    case '/':
    case '/login':
        // Redirect to the login page if visiting the base URL or "/login"
        $authController->login();
        break;
    case '/register':
        $authController->register();
        break;
    case '/logout':
        $authController->logout();
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo '404 Not Found';
        break;
}
