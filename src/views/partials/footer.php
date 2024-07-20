<?php 

require_once __DIR__ . '/../../config/detect_device.php';

?>

<footer class="<?php echo DeviceService::isMobile() ? 'mobile' : 'desktop'; ?>">
    <div class="social-container">
        <h3>Word Quest</h3>
        <br>
        <h4>Say hello at st.yahor.poresh@gmail.com</h4>
        <h4>For more info, here is my social networks</h4>
        <br>
        <br>
        <div class="social-btns">
            <button class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
            <button class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
            <button class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
            <button class="btn-icon medium primary"><i class="fa-regular fa-compass"></i></button>
        </div>
    </div>
    
    <div class="contact-form-container">
        <form id="footerForm">
            <div class="form-input-container">
                <h4>name</h4>
                <input type="text" name="footerFormName" class="form-contact-input" maxlength="36" required>
            </div>

            <div class="form-input-container">
                <h4>email</h4>
                <input type="text" name="footerFormEmail" class="form-contact-input" maxlength="36" required>
            </div>

            <div class="form-input-container">
                <h4>topic</h4>
                <input type="text" name="footerFormTopic" class="form-contact-input" maxlength="36" required>
            </div>

            <div class="form-input-container">
                <h4>message</h4>
                <textarea  type="text" name="footerFormTopic" class="form-contact-area" maxlength="36" required></textarea>
            </div>

            <br>

            <button type="submit" class="btn-round medium primary"><h5>Submit</h5></button>
        </form>
    </div>
</footer>