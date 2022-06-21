<?php  echo 'dddddddd'; ?>
<form id="dff_custom_login_form" class="dff-register-login-form" action="" method="post">
    <fieldset>
        <!-- <div class="form-group">                        
                        <input name="custom_user_login" id="custom_user_login" placeholder="<?php _e('Email', 'dff_register_login') ?>" class="required" type="text" />
                    </div> -->
        <div class="form-group">
            <!-- <label for="custom_user_Login">Username <span class="dff_required">*</span></label> -->
            <input name="dff_email_log" id="dff_email_log" placeholder="<?php _e('Email', 'dff_register_login') ?>" class="required" type="email" />
        </div>
        <div class="form-group">
            <!-- <label for="dff_user_pwd">Password <span class="dff_required">*</span></label> -->
            <input name="dff_user_pwd" id="dff_user_pwd" placeholder="<?php _e('Password', 'dff_register_login') ?>" class="required" type="password" />
        </div>
        <div class="form-group dff-login">
            <input type="hidden" name="dff_log_nonce" value="<?php echo wp_create_nonce('dff-log-nonce'); ?>" />
            <input id="custom_login_submit" type="submit" value="Login" />
        </div>
    </fieldset>
</form>

<?php

