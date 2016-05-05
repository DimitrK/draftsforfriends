<?php

if (!defined('DFF_SHARED_OPTION')) {
	define('DFF_SHARED_OPTION', 'dff_shared');
}

if (!defined('DFF_PLUGIN_VERSION_OPTION')) {
    define('DFF_PLUGIN_VERSION_OPTION', 'dff_version');
}

if (!defined('DFF_PLUGIN_VERSION_NUM')) {
    define('DFF_PLUGIN_VERSION_NUM', '2.2');
}

//https://codex.wordpress.org/Determining_Plugin_and_Content_Directories

define('DFF_ROOT_DIRNAME',  plugin_basename( dirname( __FILE__ ) ) );

define('DFF_ABS_PATH', WP_PLUGIN_DIR .'/'. DFF_ROOT_DIRNAME );

define('DFF_PHP_PATH', DFF_ABS_PATH .'/src/php' );

define('DFF_URL', plugins_url( '/'. DFF_ROOT_DIRNAME .'/src') );