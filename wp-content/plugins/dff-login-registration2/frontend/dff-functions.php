<?php


/* ------------------------------------------------------------------------- */
// Disable Admin Bar for All Users Except for Administrators
/* ------------------------------------------------------------------------- */
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}


/**
 * redirect to home page after successful login
 */

// function dff_login_redirect($redirect, $request, $user)
// {
// 	//is there a user to check?
// 	global $user;

// 	if (isset($user->roles) && is_array($user->roles)) {
// 		//check for admins
// 		if (in_array('administrator', $user->roles)) {
// 			// redirect them to the default place
// 			return home_url() . '/wp-admin/index.php';
// 		} else {
// 			return home_url() . '/my-courses';
// 		}
// 	} else {
// 		return $redirect;
// 	}
// }

// add_filter('dff_login_redirect', 'dff_login_redirect', 10, 3);

function dff_register_logininit()
{
?>

    <div class="wrap">
        <h2>There are no settings here yet</h2>
        <div>
        <?php
    }

    function wp_get_current_url()
    {
        return home_url($_SERVER['REQUEST_URI']);
    }


    
    /**
     * Function for `wp_footer` action-hook.
     * 
     * @return void
     */
    function dff_login_register_popup_action()
    {
        ?>
            <div class="register-login-module open-auth-popup modal-popup">
                <div class="modal">
                    <div class="modal-overlay modal-toggle"></div>
                    <div class="modal-wrapper modal-transition">
                        <div class="modal-header">
                            <div class="modal-close modal-toggle"></div>
                        </div>
                        <div class="modal-body">
                            <div class="modal-content register-login-tab">
                                <header class="register-login-tabs-nav">
                                    <ul>
                                        <li class="active"><a href="#tab1"><?php _e('Login', 'dff'); ?></a></li>
                                        <li><a href="#tab2"><?php _e('Register', 'dff_login_register_popup_action'); ?></a></li>
                                    </ul>
                                </header>
                                <h2 class="modal-heading"><?php _e('Future ID', 'dff_login_register_popup_action'); ?></h2>
                                <div class="register-login-tabs-content">
                                    <div class="register-login-tab-wrapper" id="tab1">
                                        <?php echo do_shortcode("[dff_login_form]"); ?>
                                    </div>
                                    <div id="tab2" class="register-login-tab-wrapper">
                                        <?php echo do_shortcode("[dff_register_form]");    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    }
    add_action('wp_footer', 'dff_login_register_popup_action');
