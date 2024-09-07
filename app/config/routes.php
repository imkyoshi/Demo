<?php

namespace app\config;

// Create a new instance of RouteCollection class
// $routes = Services::routes();

// Define default route for the app
$routes->get('/', 'AuthController::index');

// Auth routes
$routes->post('login', 'AuthController::login');
$routes->post('register', 'AuthController::register');
$routes->get('logout', 'AuthController::logout');

// You can add more routes as needed for other sections of the app.
