<?php 

require_once __DIR__ . '/../../config/detect_device.php';

?>

<header class="<?php echo DeviceService::isMobile() ? 'mobile' : 'desktop'; ?>">
    <h3>Word Quest</h3>
    <div class="header-action">
        <button class="btn-round" onclick="toggleTheme()">Toggle Theme</button>

        <div class="header-navigation">
            <?php if (!DeviceService::isMobile()): ?>
                <button style="box-shadow: none;" class="btn-round small primary"><h5>About Me</h5></button>
                <button style="box-shadow: none;" class="btn-round small primary"><h5>Contact</h5></button>
                <button style="box-shadow: none;" class="btn-round small primary"><h5>Play</h5></button>
            <?php else: ?>
                <button style="box-shadow: none;" class="btn-round small primary"><h5>Menu Icon</h5></button>
            <?php endif; ?>
        </div>
    </div>
</header>