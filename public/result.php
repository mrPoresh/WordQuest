<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';
require_once '../src/controllers/UserController.php';
require_once '../src/middleware/AuthMiddleware.php';


$authMiddleware = new AuthMiddleware($pdo);
$userId = $authMiddleware->checkSession();

if (!$userId) {
    header('Location: login.php');
    exit();
}

$title = "Result Page";
ob_start();

?>

<h2>Result Page</h2>


<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>