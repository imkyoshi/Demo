<?php
header("Content-Type: application/json");
include '../../app/config/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo, $input);
        break;
    case 'PUT':
        handlePut($pdo, $input);
        break;
    case 'DELETE':
        handleDelete($pdo, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($pdo)
{
    try {
        $sql = "SELECT * FROM users";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch users: ' . $e->getMessage()]);
    }
}

function handlePost($pdo, $input)
{
    try {
    $sql = "INSERT INTO users (fullname, address, gender, phone_number, email, password, role)
    VALUES (:fullname, :address, :gender, :phone_number, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'fullname' => $input['fullname'], 
        'address' => $input['address'], 
        'gender' => $input['gender'], 
        'phone_number' => $input['phone_number'], 
        'email' => $input['email'], 
        'password' => $input['password'], 
        'role' => $input['role']]);
    echo json_encode(['message' => 'User created successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create users: ' . $e->getMessage()]);
    }
}

function handlePut($pdo, $input)
{
    try {
    $sql = "UPDATE users SET fullname = :fullname, address = :address, gender = :gender, phone_number = :phone_number, email = :email, password = :password, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'fullname' => $input['fullname'],
        'address' => $input['address'],
        'gender' => $input['gender'],
        'phone_number' => $input['phone_number'],
        'email' => $input['email'],
        'password' => $input['password'],
        'role' => $input['role'],
        'id' => $input['id']
    ]);
    echo json_encode(['message' => 'User updated successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update users: ' . $e->getMessage()]);
    }
}


function handleDelete($pdo, $input)
{
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $input['id']]);
    echo json_encode(['message' => 'User deleted successfully']);
}
