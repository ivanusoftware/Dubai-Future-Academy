<?php

# Maintenance Mode
//define( 'VIP_MAINTENANCE_MODE', (bool) preg_match( '/^\/ar\//', $_SERVER['REQUEST_URI'] ?? '/' ) );

# Database Configuration
/*define( 'DB_NAME', 'wp_dubaifuturedev' );
define( 'DB_USER', 'dubaifuturedev' );
define( 'DB_PASSWORD', 'I8pqF-NXL4aLDiVm2Rfb' );
define( 'DB_HOST', '127.0.0.1:3306' );
define( 'DB_HOST_SLAVE', '127.0.0.1:3306' );
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_unicode_ci');
*/
/** The name of the database for WordPress */
define( 'DB_NAME', 'dubaifuture_loc_1' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );
define('ALLOW_UNFILTERED_UPLOADS', true);

$table_prefix = 'wp_';

# Security Salts, Keys, Etc
define('AUTH_KEY',         'MmkS.D9(-h+&+ps)uHQ6eV+GPo8BJ^T765762a@OZTh&l-D,<UKu#.5uqv4@#YVi');
define('SECURE_AUTH_KEY',  '``LqmQG-|dW+ImB5{?5KB{tGGRap<9Q<Z?}V|oq%U&KsfR8N5ha<:3+%dMU;hId@');
define('LOGGED_IN_KEY',    'aFZT!3V3X@vn/E;2[@4C1*$5E0[B.(OdYi!mK/+Uhgz.0v^/vA=:#-pp$e8PZu)n');
define('NONCE_KEY',        'Sh}eX$lW *k74?4I>NDDa<z KWuxjU+#-]QaP2nOE(-ijl(-zIP~j;( l*/L$Eg)');
define('AUTH_SALT',        '(Ur1`;:5!%pa5K>LLzEQ-TT@$ZW||ae3RSmZH@h2P+J%[,r>sm*[g;Ye2d+59i!+');
define('SECURE_AUTH_SALT', '4KX:l2Ik=.cL*8IE%]aH61lnI&Gl`+#||bomYWPQP$yj>NfD?K($}Q/1q~_A3KG-');
define('LOGGED_IN_SALT',   '&6>OYTz}aWMDw!u{}C.RG(t].-h0g9%ImS.p-MAu<ktY|0O&cPyS@[:KXs^bqGnD');
define('NONCE_SALT',       'aKhLiD+Ll9acN:g~N6};w-i9M_^xhrIZCi&5A`3).Dp4RPln?GY$6h5Wp;zLrKC}');


# Localized Language Stuff

/* define( 'WP_CACHE', TRUE );

define( 'WP_AUTO_UPDATE_CORE', false );

define( 'PWP_NAME', 'dubaifuturedev' );

define( 'FS_METHOD', 'direct' );

define( 'FS_CHMOD_DIR', 0775 );

define( 'FS_CHMOD_FILE', 0664 );

define( 'WPE_APIKEY', 'bf5d9831ebf5e163b87ed7f05e43fc828dc29a4e' );

define( 'WPE_CLUSTER_ID', '155297' );

define( 'WPE_CLUSTER_TYPE', 'pod' );

define( 'WPE_ISP', true );

define( 'WPE_BPOD', false );

define( 'WPE_RO_FILESYSTEM', false );

define( 'WPE_LARGEFS_BUCKET', 'largefs.wpengine' );

define( 'WPE_SFTP_PORT', 2222 );

define( 'WPE_LBMASTER_IP', '' );

define( 'WPE_CDN_DISABLE_ALLOWED', false );

define( 'DISALLOW_FILE_MODS', FALSE );

define( 'DISALLOW_FILE_EDIT', FALSE );

define( 'DISABLE_WP_CRON', false );

define( 'WPE_FORCE_SSL_LOGIN', false );

define( 'FORCE_SSL_LOGIN', false );
*/
/*SSLSTART*/ 
/*if ( isset($_SERVER['HTTP_X_WPE_SSL']) && $_SERVER['HTTP_X_WPE_SSL'] ) $_SERVER['HTTPS'] = 'on'; /*SSLEND*/

/*define( 'WPE_EXTERNAL_URL', false );

define( 'WP_POST_REVISIONS', 10 );

define( 'WPE_WHITELABEL', 'wpengine' );

define( 'WP_TURN_OFF_ADMIN_BAR', false );

define( 'WPE_BETA_TESTER', false );

umask(0002);

$wpe_cdn_uris=array ( );

$wpe_no_cdn_uris=array ( );

$wpe_content_regexs=array ( );

$wpe_all_domains=array ( 0 => 'dubaifuturedev.wpengine.com', );

$wpe_varnish_servers=array ( 0 => 'pod-155297', );

$wpe_special_ips=array ( 0 => '34.76.150.73', );

$wpe_netdna_domains=array ( );

$wpe_netdna_domains_secure=array ( );

$wpe_netdna_push_domains=array ( );

$wpe_domain_mappings=array ( );

$memcached_servers=array ( 'default' =>  array ( 0 => 'unix:///tmp/memcached.sock', ), );

define( 'WPE_SFTP_ENDPOINT', '' );*/


define('WPLANG','');

# WP Engine ID


# WP Engine Settings
define('WP_DEBUG', false);
ini_set('display_errors','On');
ini_set('error_reporting', E_ALL );

// Enable Debug logging to the /wp-content/debug.log file
// define( 'WP_DEBUG_LOG', '/tmp/wp-errors.log' );
define( 'WP_DEBUG_LOG', true );

// Disable display of errors and warnings
define( 'WP_DEBUG_DISPLAY', true);

// Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
define( 'SCRIPT_DEBUG', false );

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define( 'DOMAIN_CURRENT_SITE', 'dubaifuture.loc' ); 
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

//define( 'ALLOW_UNFILTERED_UPLOADS', true );

# That's It. Pencils down
if ( !defined('ABSPATH') )
	define('ABSPATH', __DIR__ . '/');
require_once(ABSPATH . 'wp-settings.php');
