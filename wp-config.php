<?php

define('HTTPS', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true);

// Load database info and local development parameters
if (file_exists(dirname(__FILE__) . '/config.php')) {
    include dirname(__FILE__) . '/config.php';
} else {
    die('<h1>Config file not found.</h1>');
}

// Custom Content Directory
define('WP_CONTENT_DIR', dirname(__FILE__) . '/content');
define('WP_CONTENT_URL', (HTTPS ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . '/content');

// You almost certainly do not want to change these
define('DB_CHARSET', 'utf8');

if (!defined('DB_COLLATE')) {
    define('DB_COLLATE', '');
}

define('WPCF7_AUTOP', false);
define('DISALLOW_FILE_EDIT', true);
define('WP_POST_REVISIONS', 20);

// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
define('AUTH_KEY',         '6:$p)]ouD>-@-w4xTu@}sY$<hpfXqkjWhgtOeE)It>d{f-FO2J;Fik7a$s#B?Ihx');
define('SECURE_AUTH_KEY',  '# q|w8 %iT++4r}|~4QiC32r~4Z2oZ676(gHq(P:YKY-1hr2J#CJgETRx6YTqx!<');
define('LOGGED_IN_KEY',    '@G~kdEOk0Vzg{^3Wepb}+I:!!AY]~Yaa((s}df.+IL _A:-DQ ddE~M!2x0=6tAc');
define('NONCE_KEY',        'N)kT-4%CTJG`4`>NevH2~Ywy5iN8|~6lkFO!s(>CAwg~[}2]GF7ve5.G:41`iaH-');
define('AUTH_SALT',        'F-P?E@{Fq2MFt~%?nJ+G_1WXKlGZ!B#cTTe-FWA,x+a~@g`Nl_bcB4V*]awq$QOH');
define('SECURE_AUTH_SALT', 'XFDfv]ZSJaK-+9oY--,]!WSkXb:tA+ishgdw({3+8y_m=e8-_aEFe0(E43s:,FY]');
define('LOGGED_IN_SALT',   'Y*x(F.}+#k3#Vu|8{8;dL.=cX0A-@6w0qY-E9nO0uaHSz10Mq!;|54AYgs8dEtwT');
define('NONCE_SALT',       '=|}n5sifnV+4#BLwCbq@##%M,C2sZVV(|P[fVT6jS8:R,wf#{z%x,)V5H*V5--Cy');


// Table prefix
// Change this if you have multiple installs in the same database
$table_prefix = "cardinal_pE4c1A_";

// Language
// Leave blank for American English
if (!defined('WPLANG')) {
    define('WPLANG', 'fr_FR');
}

if (!defined('WP_DEBUG')) {
    define('WP_DEBUG', false);
}
if (WP_DEBUG) {
    ini_set('display_errors', 1);
    define('WP_DEBUG_DISPLAY', false);

    if (!defined('WP_SITEURL')) {
        define('WP_SITEURL', (HTTPS ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] .'/wp/');
    }
    if (!defined('WP_HOME')) {
        define('WP_HOME', (HTTPS ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);
    }
}
else {
    // Hide errors
    ini_set('display_errors', 0);
    define('WP_DEBUG_DISPLAY', false);
}

// Load a Memcached config if we have one
if (file_exists(dirname(__FILE__) . '/memcached.php')) {
    $memcached_servers = include dirname(__FILE__) . '/memcached.php';
}

// Bootstrap WordPress
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/wp/');
}
require_once(ABSPATH . 'wp-settings.php');
