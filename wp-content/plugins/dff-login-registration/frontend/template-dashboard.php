<?php
// echo 'dashboard';
get_header();
echo file_get_contents(plugin_dir_path( __FILE__ ) . 'build/index.html');
get_footer();
