<?php

class DeviceService {
    public static function isMobile() {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        return preg_match('/Mobile|Android|BlackBerry|iPhone|Windows Phone/', $user_agent);
    }
}

?>

<!-- <div class="game-wrapper <?php echo DeviceService::isMobile() ? 'mobile' : 'desktop'; ?>"> -->