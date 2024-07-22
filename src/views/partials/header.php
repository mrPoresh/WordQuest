<?php 

require_once __DIR__ . '/../../config/detect_device.php';
require_once __DIR__ . '/../../middleware/AuthMiddleware.php';

$authMiddleware = new AuthMiddleware($pdo);
$userId = $authMiddleware->checkSession();

$isLogged = $userId ? true : false;

?>

<header id="main-header" class="<?php echo DeviceService::isMobile() ? 'mobile' : 'desktop'; ?>">
    <h3>Word Quest</h3>
    <div class="header-action">
        <div appToggBtn id="theme-toggle-btn"></div>

        <div class="header-navigation">
            <?php if (!DeviceService::isMobile()): ?>
                <button style="box-shadow: none;" class="btn-round small primary"><h5>About Me</h5></button>
                <button style="box-shadow: none;" class="btn-round small primary"><h5>Contact</h5></button>
                <button style="box-shadow: none; display: <?php echo $isLogged ? 'inline-block' : 'none'; ?>" class="btn-round small primary" onclick="navigate('user.php')"><h5>Profile</h5></button>
                <button style="box-shadow: none; display: <?php echo $isLogged ? 'none' : 'inline-block'; ?>" class="btn-round small primary" onclick="navigate('login.php')"><h5>Login</h5></button>
                <button style="box-shadow: none;" class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
            <?php else: ?>
                <button style="box-shadow: none;" class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
            <?php endif; ?>
        </div>
    </div>
</header>

