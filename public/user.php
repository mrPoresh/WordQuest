<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/UserController.php';


$authController = new AuthController($pdo);

if (!isset($_SESSION['auth_token'])) {
    header('Location: login.php');
    exit();
}

$token = $_SESSION['auth_token'];
error_log("SESSION: $token");
$userId = $authController->verifyToken($token);

if (!$userId) {
    header('Location: login.php');
    exit();
}

$userController = new UserController($pdo);

$user = $userController->getUserById($userId);
$score = $userController->getUserScore($userId);

$title = "User Page";
ob_start();

?>

<h2>User Page</h2>
<p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
<p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
<p>Current Score: <?php echo htmlspecialchars($score['current_score']); ?></p>
<p>Lats Score: <?php echo htmlspecialchars($score['last_score']); ?></p>
<button onclick="logout()">Logout</button>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>