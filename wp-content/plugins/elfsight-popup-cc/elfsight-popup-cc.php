<?php
/*
Plugin Name: Elfsight Popup CC
Description: Create various types of popups for your website for more leads and higher sales.
Plugin URI: https://elfsight.com/popup-widget/codecanyon/?utm_source=markets&utm_medium=codecanyon&utm_campaign=popup&utm_content=plugin-site
Version: 1.1.0
Author: Elfsight
Author URI: https://elfsight.com/?utm_source=markets&utm_medium=codecanyon&utm_campaign=popup&utm_content=plugins-list
*/

if (!defined('ABSPATH')) exit;


require_once('core/elfsight-plugin.php');

$elfsight_popup_config_path = plugin_dir_path(__FILE__) . 'config.json';
$elfsight_popup_config = json_decode(file_get_contents($elfsight_popup_config_path), true);

new ElfsightPopupPlugin(
    array(
        'name' => esc_html__('Popup'),
        'description' => esc_html__('Create various types of popups for your website for more leads and higher sales.'),
        'slug' => 'elfsight-popup',
        'version' => '1.1.0',
        'text_domain' => 'elfsight-popup',

        'editor_settings' => $elfsight_popup_config['settings'],
        'editor_preferences' => $elfsight_popup_config['preferences'],

        'plugin_name' => esc_html__('Elfsight Popup'),
        'plugin_file' => __FILE__,
        'plugin_slug' => plugin_basename(__FILE__),

        'vc_icon' => plugins_url('assets/img/vc-icon.png', __FILE__),
        'menu_icon' => plugins_url('assets/img/menu-icon.svg', __FILE__),

        'update_url' => esc_url('https://a.elfsight.com/updates/v1/'),
        'product_url' => esc_url('https://codecanyon.net/item/elfsight-popup/25098865?ref=Elfsight'),
        'helpscout_plugin_id' => 110717
    )
);

?>
