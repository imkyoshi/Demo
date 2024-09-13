<?php
namespace app\api;
require_once __DIR__ . '/../../vendor/autoload.php';

use app\config\Connection; // My DB Connection
use app\model\AuthDAL; // My Data Layer
use app\controller\AuthController; // My Controller
use app\Helpers\Cookies;

header("Content-Type: application/json");

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$connection = new Connection();
$pdo = $connection->connect();

$authDAL = new AuthDAL($pdo);
$cookies = new Cookies();
$authController = new AuthController($authDAL, $cookies);

error_log('Received request method: ' . $requestMethod);
error_log('Received POST data: ' . print_r($_POST, true));

switch ($requestMethod) {
    case 'POST':
        // Retrieve and validate the 'action' field
        $action = $_POST['action'] ?? '';
        if (!$action) {
            echo json_encode(['error' => 'No action specified']);
            http_response_code(400);
            exit;
        }

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
                echo json_encode(['error' => 'Invalid action']);
                http_response_code(400);
                break;
        }
        break;

    default:
        echo json_encode(['error' => 'Unsupported request method']);
        http_response_code(405);
        break;
}
