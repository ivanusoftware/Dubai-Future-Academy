<?php
// echo 'dashboard';
// include plugin_dir_url( __FILE__ ) . 'build/index.html';
echo file_get_contents(plugin_dir_path( __FILE__ ) . 'build/index.html');
