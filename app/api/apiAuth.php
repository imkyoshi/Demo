<?php
namespace app\api;
require_once __DIR__ . '/../../vendor/autoload.php';

use app\config\Connection;
use app\model\AuthDAL;
use app\controller\AuthController1;
use app\Helpers\Cookies;

// Initialize database connection
$connection = new Connection();
$pdo = $connection->connect();

$authDAL = new AuthDAL($pdo);
$cookies = new Cookies();
$authController = new AuthController1($authDAL, $cookies);

// header("Content-Type: application/json");
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'login':
            $authController->login();
            break;
        case 'register':
            $authController->register();
            break;
        case 'logout':
            $authController->logout();
            break;    
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action.']);
            break;
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
}
