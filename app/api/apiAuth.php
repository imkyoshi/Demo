<!-- THIS IS API ENDPOINTS -->
<?php
namespace app\api;

use app\config\Connection; // My DB Connection
use app\model\AuthDAL; // My Data Layer
use app\controller\AuthController; // My Controller

header("Content-Type: application/json");
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$connection = new Connection();
$pdo = $connection->connect();

$authDAL = new AuthDAL($pdo);
$authController = new AuthController($authDAL);

error_log( 'Received request method: ' . $requestMethod);
error_log('Received POST data: ' . print_r($_POST, true));

switch ($requestMethod) {
    case 'POST':
        $action = $_POST['action'] ?? '';
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
