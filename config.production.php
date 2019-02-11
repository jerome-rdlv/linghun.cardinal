<?php
define('DB_NAME', '<DB_NAME>');
define('DB_USER', '<DB_USER>');
define('DB_PASSWORD', '<DB_PASS>');
define('DB_HOST', '<DB_HOST>');

// debug
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);

// defined already by wp-cli
if (!defined('WP_CLI')) {
    define('WP_DEBUG_DISPLAY', false);
}

//define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] .'/wp/');
//define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
