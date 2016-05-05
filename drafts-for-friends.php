<?php
/*
Plugin Name: Drafts for Friends
Plugin URI: http://automattic.com/
Description: Now you don't need to add friends as users to the blog in order to let them preview your drafts
Author: Neville Longbottom
Version: 3.0
Author URI:
Text Domain: draftsforfriends
*/ 

//namespace DFF;


require_once('constants.php');
require_once('src/php/plugin.php');
require_once('src/php/installation.php');

use DFF\Plugin as Draft_For_Friends_Plugin;
use DFF\Installation;	


$installation = new Installation( get_bloginfo('version'), PHP_VERSION );


if ( $installation->meets_requirements() ) {
	new Draft_For_Friends_Plugin();
} else {
	add_action('admin_notices', 'fail_requirements_notice');	
}

function fail_requirements_notice(){
	$fail_message = sprintf( 
		__( 
			'Your current WP (%1$s) or PHP (%2$s) version does not meet the minimum requirements (PHP: %3$s, WP: %4$s) for Drafts for Friends.', 
			'draftsforfriends' 
		), 
		get_bloginfo('version'), 
		PHP_VERSION, 
		Installation::$MIN_WP_VERSION, 
		Installation::$MIN_PHP_VERSION
	);
	
	echo '<div class="error">
		 <p>'. $fail_message .'</p>
	</div>';
}