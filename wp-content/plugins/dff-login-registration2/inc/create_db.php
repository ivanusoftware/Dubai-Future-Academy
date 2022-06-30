<?php
global $wpdb;
$table_name = $wpdb->base_prefix . "dff_future_users";
$charset_collate = $wpdb->get_charset_collate();

if ($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {

    $sql = "CREATE TABLE $table_name (
        ID mediumint(9) NOT NULL AUTO_INCREMENT,
               `future_user_id` varchar(200) NOT NULL,
               `course_en_id` varchar(255) NULL,
               `course_ar_id` varchar(255) NULL,         
               `user_date` datetime NOT NULL,
               `user_date_gmt` datetime NOT NULL,

        PRIMARY KEY (ID)
) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    //DB for forms
   
}


$table_future_usemeta = $wpdb->base_prefix . "dff_future_usemeta";
// $charset_collate = $wpdb->get_charset_collate();
$sql_usmate = "CREATE TABLE $table_future_usemeta (
 dff_umeta_id bigint(20) NOT NULL AUTO_INCREMENT,
    `dff_future_user_id` bigint(20) NOT NULL,
    `dff_meta_key` varchar(255) NULL,
    `dff_meta_value` varchar(255) NULL,
    `user_date` datetime NOT NULL,
    `user_date_gmt` datetime NOT NULL,
 PRIMARY KEY (dff_umeta_id)
) $charset_collate;";

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql_usmate);