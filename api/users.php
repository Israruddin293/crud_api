<?php
session_start();
include __DIR__ . '/db.php';
include __DIR__ . '/user.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    error_log("Unauthorized access attempt.");
    exit;
}

$user = new User($conn);
$role = $_SESSION['user']['role'];

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (!$conn) {
            http_response_code(500);
            echo json_encode(['message' => 'Database connection failed']);
            error_log("Database connection failed.");
            exit;
        }

        $users = $user->read();
        if ($users === false) {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to fetch users']);
            error_log("Failed to fetch users from the database.");
        } else {
            echo json_encode($users);
        }
        break;

    case 'POST':
        if ($role !== 'admin') {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden']);
            error_log("Forbidden: User does not have admin privileges.");
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['name'], $data['email'])) {
            $result = $user->create($data['name'], $data['email']);
            echo json_encode(['message' => $result ? 'User added successfully' : 'Failed to add user']);
            error_log($result ? "User added successfully." : "Failed to add user.");
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
            error_log("Invalid input for creating user.");
        }
        break;

    case 'PUT':
        if ($role !== 'admin') {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden']);
            error_log("Forbidden: User does not have admin privileges.");
            exit;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['id'], $data['name'], $data['email'])) {
            $result = $user->update($data['id'], $data['name'], $data['email']);
            echo json_encode(['message' => $result ? 'User updated successfully' : 'Failed to update user']);
            error_log($result ? "User updated successfully." : "Failed to update user.");
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
            error_log("Invalid input for updating user.");
        }
        break;

    case 'DELETE':
        if ($role !== 'admin') {
            http_response_code(403);
            echo json_encode(['message' => 'Forbidden']);
            error_log("Forbidden: User does not have admin privileges.");
            exit;
        }
        if (isset($_GET['id'])) {
            $result = $user->delete($_GET['id']);
            echo json_encode(['message' => $result ? 'User deleted successfully' : 'Failed to delete user']);
            error_log($result ? "User deleted successfully." : "Failed to delete user.");
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input']);
            error_log("Invalid input for deleting user.");
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method not allowed']);
        error_log("Method not allowed: " . $_SERVER['REQUEST_METHOD']);
        break;
}
?>
