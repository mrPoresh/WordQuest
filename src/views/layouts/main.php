<?php

require_once __DIR__ . '/../../config/detect_device.php';

ob_start();

require (DeviceService::isMobile() ? __DIR__ . '/../layouts/mobile.php' : __DIR__ . '/../layouts/desktop.php');

$content = ob_get_clean();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/public/assets/css/variables.css">
    <link rel="stylesheet" href="/public/assets/css/global.css">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <?php echo $content; ?>
    <script src="/public/assets/js/script.js"></script>
</body>
</html>