<?php

$title = "Home";
ob_start();

?>

<div style="width: 100%; display: flex; flex-direction: column; gap: 16px; text-align: left;">
    <h1>Hello World!</h1>
    <h2>Hello World!</h2>
    <h3>Hello World!</h3>
    <h4>Hello World!</h4>
    <h5>Hello World!</h5>
    <p>Hello World!</p>
</div>


<button onclick="logout()">Logout</button>


<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>
