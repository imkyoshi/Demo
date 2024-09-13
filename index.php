<?php

// Autoload necessary classes (you can adjust this based on your autoload setup)

use app\config\Connection;
use app\model\AuthDAL;
use app\controller\AuthController;
use app\Helpers\Cookies;

// Parse URL
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$path = $request_uri[0];

$connection = new Connection();
$pdo = $connection->connect();
$authDAL = new AuthDAL($pdo);
$cookies = new Cookies();
$authController = new AuthController($authDAL, $cookies);

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
