<?php
// echo 'Login';
// echo plugin_dir_url( __FILE__ );

// echo plugin_dir_url( __FILE__ ) . 'build/index.html';

echo file_get_contents(plugin_dir_url( __FILE__ ) . 'build/index.html');
// include plugin_dir_url( __FILE__ ) . 'build/index.html';
