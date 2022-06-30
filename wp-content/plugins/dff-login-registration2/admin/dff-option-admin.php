<?php
add_action('admin_menu', 'dff_register_login_plugin_setup_menu');
function dff_register_login_plugin_setup_menu()
{
    // add_menu_page( 'Register Plugin Page', 'Register Plugin', 'manage_options', 'dff_plugin','dff_init', 'dff_custom_registration_form' );
    add_menu_page('DFF Login and Register Plugin Page', 'DFF Login and Register', 'manage_options', 'dff_register_login_plugin', 'dff_register_login_init');
}
function dff_register_login_init()
{
    global $custom_load_css;

    // set this to true so the CSS is loaded
    // $custom_load_css = true;
    $dff_reg_options = get_option('dff_reg_options'); //captcha_login
    $dff_api_url    = isset($dff_reg_options['dff_api_url']) ? $dff_reg_options['dff_api_url'] : '';
    $dff_api_url_future_user    = isset($dff_reg_options['dff_api_url_future_user']) ? $dff_reg_options['dff_api_url_future_user'] : '';
    $language      = isset($dff_reg_options['dff_language']) ? $dff_reg_options['dff_language'] : '';

    // call to save the setting options
    dff_login_register_save_options();

?>

    <div class="wrap dff-login-register-admin">

        <!-- <div id="icon-options-general" class="icon32"></div> -->
        <h2><?php _e('DFF Login and Register', 'dff-login-register'); ?></h2>

        <!-- <p>Protect WordPress Custom login, Custom registration</p> -->

        <?php
        if (isset($_GET['settings-updated']) && ($_GET['settings-updated'])) {
            echo '<div id="message" class="updated"><p><strong>Settings saved. </strong></p></div>';
        }
        ?>
        <div id="poststuff">

            <div id="post-body" class="metabox-holder columns-2">

                <!-- main content -->
                <!-- <div id="post-body-content">

                    <div class="meta-box-sortables ui-sortable"> -->

                <form method="post" class="posttypesui">
                    <?php wp_nonce_field('update-options') ?>
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">
                                <span><?php _e('General Settings', 'dff-login-register'); ?></span>
                            </h2>
                        </div>
                        <div class="inside">
                            <!-- <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="theme"><?php _e('Language', 'dff-login-register'); ?></label>
                                    </th>
                                    <td>
                                        <select id="theme" name="dff_reg_options[language]">
                                            <?php
                                            $languages = array(
                                                __('Auto Detect', 'dff-login-register')         => '',
                                                __('English', 'dff-login-register')             => 'en',
                                                __('Arabic', 'dff-login-register')              => 'ar',
                                                __('Bulgarian', 'dff-login-register')           => 'bg',
                                                __('Catalan Valencian', 'dff-login-register')   => 'ca',
                                                __('Czech', 'dff-login-register')               => 'cs',
                                                __('Danish', 'dff-login-register')              => 'da',
                                                __('German', 'dff-login-register')              => 'de',
                                                __('Greek', 'dff-login-register')               => 'el',
                                                __('British English', 'dff-login-register')     => 'en_gb',
                                                __('Spanish', 'dff-login-register')             => 'es',
                                                __('Persian', 'dff-login-register')             => 'fa',
                                                __('French', 'dff-login-register')              => 'fr',
                                                __('Canadian French', 'dff-login-register')     => 'fr_ca',
                                                __('Hindi', 'dff-login-register')               => 'hi',
                                                __('Croatian', 'dff-login-register')            => 'hr',
                                                __('Hungarian', 'dff-login-register')           => 'hu',
                                                __('Indonesian', 'dff-login-register')          => 'id',
                                                __('Italian', 'dff-login-register')             => 'it',
                                                __('Hebrew', 'dff-login-register')              => 'iw',
                                                __('Jananese', 'dff-login-register')            => 'ja',
                                                __('Korean', 'dff-login-register')              => 'ko',
                                                __('Lithuanian', 'dff-login-register')          => 'lt',
                                                __('Latvian', 'dff-login-register')             => 'lv',
                                                __('Dutch', 'dff-login-register')               => 'nl',
                                                __('Norwegian', 'dff-login-register')           => 'no',
                                                __('Polish', 'dff-login-register')              => 'pl',
                                                __('Portuguese', 'dff-login-register')          => 'pt',
                                                __('Romanian', 'dff-login-register')            => 'ro',
                                                __('Russian', 'dff-login-register')             => 'ru',
                                                __('Slovak', 'dff-login-register')              => 'sk',
                                                __('Slovene', 'dff-login-register')             => 'sl',
                                                __('Serbian', 'dff-login-register')             => 'sr',
                                                __('Swedish', 'dff-login-register')             => 'sv',
                                                __('Thai', 'dff-login-register')                => 'th',
                                                __('Turkish', 'dff-login-register')             => 'tr',
                                                __('Ukrainian', 'dff-login-register')           => 'uk',
                                                __('Vietnamese', 'dff-login-register')          => 'vi',
                                                __('Simplified Chinese', 'dff-login-register')  => 'zh_cn',
                                                __('Traditional Chinese', 'dff-login-register') => 'zh_tw'
                                            );

                                            foreach ($languages as $key => $value) {
                                                echo "<option value='$value'" . selected($value, $language, true) . ">$key</option>";
                                            }
                                            ?>
                                        </select>

                                        <p class="description">
                                            <?php _e('Forces the widget to render in a specific language', 'dff-login-register'); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table> -->
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="site-key"><?php _e('DFF API Url', 'dff-login-register'); ?></label></th>
                                    <td>

                                        <input id="dff-api-url" type="text" name="dff_reg_options[dff_api_url]" value="<?php echo $dff_api_url; ?>">
                                        <p class="description">
                                            <?php _e('Future ID API URL', 'dff-login-register'); ?>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="site-key"><?php _e('DFF API Url Future ID', 'dff-login-register'); ?></label></th>
                                    <td>
                                        <input id="dff-api-url" type="text" name="dff_reg_options[dff_api_url_future_user]" value="<?php echo $dff_api_url_future_user; ?>">
                                        <p class="description">
                                            <?php _e('API URL get user data from Future Id', 'dff-login-register'); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            <p>
                                <?php wp_nonce_field('settings_nonce'); ?>
                                <input class="button-primary" type="submit" name="settings_submit" value="Save">
                            </p>
                        </div>
                    </div>
                </form>
                <!-- </div>
                </div> -->

            </div>
        </div>
    </div>
<?php
    // html_form_code();
}

// dff_login_plugin - save_options
function dff_login_register_save_options()
{
    if (isset($_POST['settings_submit']) && check_admin_referer('settings_nonce', '_wpnonce')) {

        $saved_options = $_POST['dff_reg_options'];
        update_option('dff_reg_options', $saved_options);
        //  crlfwnr_custom_registration_form();
        wp_redirect('?page=dff_register_login_plugin&settings-updated=true');
    }
}
