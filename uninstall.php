<?php

//https://developer.wordpress.org/plugins/the-basics/uninstall-methods/

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

delete_option( $DFF_SHARED_OPTION );
delete_option( $DFF_PLUGIN_VERSION_OPTION );