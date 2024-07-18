<?php

require_once '../../../src/config/session.php';
require_once '../../../src/config/database.php';
require_once '../../../src/controllers/AuthController.php';

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController($pdo);
    $data = json_decode(file_get_contents('php://input'), true);
    
    $username = $data['username'];
    $email = $data['email'];
    $password = $data['password'];

    $token = $authController->signup($username, $email, $password);

    if ($token) {
        $_SESSION['auth_token'] = $token;

        echo json_encode(['success' => true, 'token' => $token]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Registration failed']);
    }

    exit();

} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

?>