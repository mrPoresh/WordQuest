<?php

$title = "Home";
ob_start();

?>



<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>
