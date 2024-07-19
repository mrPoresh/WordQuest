<?php 

require_once __DIR__ . '/../../config/detect_device.php';

?>

<header class="<?php echo DeviceService::isMobile() ? 'mobile' : 'desktop'; ?>">
    <p>Word Quest</p>
    <div>
        <button class="button" onclick="toggleTheme()">Toggle Theme</button>
        <p>Menu Icon</p>
    </div>
</header>