<?php

require_once '../src/config/session.php';
require_once '../src/config/database.php';
require_once '../src/controllers/AuthController.php';


$authController = new AuthController($pdo);

if (isset($_SESSION['auth_token'])) {
    $token = $_SESSION['auth_token'];
    $userId = $authController->verifyToken($token);

    if ($userId) {
        header('Location: index.php');
        exit();
    }
}

$title = "Sign Up";
ob_start();

?>

<div class="signup-form-container">
    <h2>Sign Up</h2>

    <form id="signupForm">
        <div class="form-input-container">
            <h4>username</h4>
            <input type="text" id="username" name="username" class="form-contact-input" maxlength="36" required>
        </div>

        <div class="form-input-container">
            <h4>email</h4>
            <input type="email" id="email" name="email" class="form-contact-input" maxlength="36" required>
        </div>

        <div class="form-input-container">
            <h4>password</h4>
            <input type="password" id="password" name="password" class="form-contact-input" maxlength="36" required>
        </div>

        <p>Already have an account?<a href="login.php">Login</a></p>

        <br>

        <button type="submit" class="btn-round medium primary"><h5>Submit</h5></button>
    </form>
</div>

<?php
    $content = ob_get_clean();
    require '../src/views/layouts/main.php';
?>