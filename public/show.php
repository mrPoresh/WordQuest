<?php

$title = "Show";
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
<br>
<div style="width: 100%; display: flex; flex-direction: column; gap: 32px; text-align: left;">
    <div style="width: 100%; display: flex; flex-direction: row; gap: 16px; text-align: left; aligin-items: center; justify-content: space-around">
        <button class="btn-round small primary"><h5>Play</h5></button>
        <button class="btn-round small warn"><h5>Play</h5></button>
        <button class="btn-round small accent"><h5>Play</h5></button>
    </div>

    <div style="width: 100%; display: flex; flex-direction: row; gap: 16px; text-align: left; aligin-items: center; justify-content: space-around">
        <button class="btn-round medium primary"><h5>Play</h5></button>
        <button class="btn-round medium warn"><h5>Play</h5></button>
        <button class="btn-round medium accent"><h5>Play</h5></button>
    </div>

    <div style="width: 100%; display: flex; flex-direction: row; gap: 16px; text-align: left; aligin-items: center; justify-content: space-around">
        <button class="btn-round large primary"><h5>Play</h5></button>
        <button class="btn-round large warn"><h5>Play</h5></button>
        <button class="btn-round large accent"><h5>Play</h5></button>
    </div>

    <div style="width: 100%; display: flex; flex-direction: row; gap: 16px; text-align: left; aligin-items: center; justify-content: space-around">
        <button class="btn-icon small primary"><i class="fa-regular fa-compass"></i></button>
        <button class="btn-icon small warn"><i class="fa-regular fa-compass"></i></button>
        <button class="btn-icon small accent"><i class="fa-regular fa-compass"></i></button>
    </div>

    <div style="width: 100%; display: flex; flex-direction: row; gap: 16px; text-align: left; aligin-items: center; justify-content: space-around">
        <button class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
        <button class="btn-icon medium warn"><i class="fa-regular fa-compass"></i></button>
        <button class="btn-icon medium accent"><i class="fa-regular fa-compass"></i></button>
    </div>

    <div style="width: 100%; display: flex; flex-direction: row; gap: 16px; text-align: left; aligin-items: center; justify-content: space-around">
        <button class="btn-icon large primary"><i class="fa-regular fa-compass"></i></button>
        <button class="btn-icon large warn"><i class="fa-regular fa-compass"></i></button>
        <button class="btn-icon large accent"><i class="fa-regular fa-compass"></i></button>
    </div>
</div>

<?php

$content = ob_get_clean();
require __DIR__ . '/../src/views/layouts/main.php';

?>